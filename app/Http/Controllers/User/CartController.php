<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        $cartItems = [];
        $total = 0;

        foreach ($cart as $item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                $subtotal = $product->price * $item['qty'];
                $total += $subtotal;
                $cartItems[] = [
                    'product' => $product,
                    'qty' => $item['qty'],
                    'subtotal' => $subtotal,
                    'variants' => $item['variants'] ?? null
                ];
            }
        }

        return view('user.cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
            'variants' => 'nullable|array'
        ]);

        $product = Product::find($validated['product_id']);
        $variants = $request->input('variants', null);
        
        $cart = Session::get('cart', []);
        
        $found = false;
        foreach ($cart as &$item) {
            $itemVariants = $item['variants'] ?? null;
            if ($item['product_id'] == $validated['product_id'] && $itemVariants == $variants) {
                if ($product->stock < $item['qty'] + $validated['qty']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak mencukupi'
                    ]);
                }

                $item['qty'] += $validated['qty'];
                $found = true;
                break;
            }
        }

        if (!$found) {
            if ($product->stock < $validated['qty']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi'
                ]);
            }

            $cart[] = [
                'product_id' => $validated['product_id'],
                'qty' => $validated['qty'],
                'variants' => $variants
            ];
        }

        Session::put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Produk ditambahkan ke keranjang',
            'count' => count($cart)
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
            'variants' => 'nullable|array'
        ]);

        $cart = Session::get('cart', []);
        $variants = $request->input('variants', null);
        
        foreach ($cart as &$item) {
            $itemVariants = $item['variants'] ?? null;
            if ($item['product_id'] == $validated['product_id'] && $itemVariants == $variants) {
                $product = Product::find($item['product_id']);
                if ($product->stock >= $validated['qty']) {
                    $item['qty'] = $validated['qty'];
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak mencukupi'
                    ]);
                }
                break;
            }
        }

        Session::put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Keranjang diperbarui'
        ]);
    }

    public function remove(Request $request, $productId)
    {
        $cart = Session::get('cart', []);
        $variants = $request->input('variants', null);
        
        $cart = array_filter($cart, function($item) use ($productId, $variants) {
            $itemVariants = $item['variants'] ?? null;
            return !($item['product_id'] == $productId && $itemVariants == $variants);
        });
        
        Session::put('cart', array_values($cart));

        return response()->json([
            'success' => true,
            'message' => 'Produk dihapus dari keranjang'
        ]);
    }

    public function count()
    {
        $cart = Session::get('cart', []);
        return response()->json(['count' => count($cart)]);
    }

    public function checkout(Request $request)
    {
        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('user.products.index')
                ->with('error', 'Keranjang kosong');
        }

        $selectedIndices = $request->query('selected_indices');
        $cartItems = [];
        $total = 0;

        if ($selectedIndices !== null && $selectedIndices !== '') {
            $indices = explode(',', $selectedIndices);
        } else {
            $indices = array_keys($cart);
        }

        foreach ($cart as $key => $item) {
            if (in_array((string)$key, $indices) || in_array($key, $indices)) {
                $product = Product::find($item['product_id']);
                if (!$product) {
                    return back()->with('error', 'Salah satu produk di keranjang sudah tidak tersedia');
                }

                if ($product->stock < $item['qty']) {
                    return back()->with('error', "Stok {$product->name} tidak mencukupi");
                }

                $subtotal = $product->price * $item['qty'];
                $total += $subtotal;
                $cartItems[] = [
                    'product' => $product,
                    'qty' => $item['qty'],
                    'subtotal' => $subtotal,
                    'variants' => $item['variants'] ?? null,
                    'cart_index' => $key
                ];
            }
        }

        if (empty($cartItems)) {
            return redirect()->route('user.cart.index')->with('error', 'Pilih setidaknya satu produk untuk dibeli.');
        }

        $selectedIndicesStr = implode(',', $indices);

        return view('user.cart.checkout', compact('cartItems', 'total', 'selectedIndicesStr'));
    }

    public function processCheckout(Request $request)
    {
        $validated = $request->validate([
            'address' => 'required|string',
            'phone' => 'required|string',
            'selected_indices' => 'required|string',
        ]);

        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong');
        }

        $selectedIndices = explode(',', $validated['selected_indices']);

        $result = DB::transaction(function () use ($cart, $validated, $selectedIndices) {
            $products = [];

            foreach ($selectedIndices as $index) {
                if (!isset($cart[$index])) {
                    continue;
                }
                $item = $cart[$index];
                $product = Product::lockForUpdate()->find($item['product_id']);

                if (!$product) {
                    return ['error' => 'Salah satu produk di keranjang Anda sudah tidak tersedia'];
                }

                if ($product->stock < $item['qty']) {
                    return ['error' => "Stok {$product->name} tidak mencukupi"];
                }

                $products[] = [$product, $item['qty'], $item['variants'] ?? null, $index];
            }

            if (empty($products)) {
                return ['error' => 'Tidak ada produk terpilih untuk dibeli'];
            }

            $total = 0;
            $orderProducts = [];

            foreach ($products as [$product, $qty, $variants, $index]) {
                $subtotal = $product->price * $qty;
                $total += $subtotal;
                
                $variantString = '';
                if (is_array($variants) && count($variants) > 0) {
                    $variantParts = [];
                    foreach ($variants as $k => $v) {
                        $variantParts[] = "$k: $v";
                    }
                    $variantString = ' (' . implode(', ', $variantParts) . ')';
                }

                $orderProducts[] = [
                    'id' => $product->id,
                    'name' => $product->name . $variantString,
                    'qty' => $qty,
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                    'image' => $product->image,
                ];
            }

            foreach ($products as [$product, $qty, $variants, $index]) {
                $product->decrement('stock', $qty);
            }

            $user = auth()->user();
            $order = Order::create([
                'user_id' => $user->id,
                'order_id' => 'ORD-' . strtoupper(Str::random(8)),
                'products' => $orderProducts,
                'total' => $total,
                'status' => 'diproses',
            ]);

            $user->update([
                'address' => $validated['address'],
                'phone' => $validated['phone'],
            ]);

            return ['order' => $order, 'processed_indices' => $selectedIndices];
        });

        if (isset($result['error'])) {
            return back()->with('error', $result['error']);
        }

        // Hapus hanya produk yang diproses dari keranjang belanja
        $processedIndices = $result['processed_indices'];
        foreach ($processedIndices as $index) {
            unset($cart[$index]);
        }
        Session::put('cart', array_values($cart));

        return redirect()->route('user.orders.index')
            ->with('success', 'Pesanan berhasil dibuat!');
    }

    public function clear()
    {
        Session::forget('cart');
        return response()->json([
            'success' => true,
            'message' => 'Keranjang dikosongkan'
        ]);
    }
}
