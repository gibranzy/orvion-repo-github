<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::where('user_id', Auth::id());

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('user.orders.index', compact('orders'));
    }

    public function show(int $id)
    {
        $order = Order::where('user_id', Auth::id())
            ->findOrFail($id);

        return view('user.orders.show', compact('order'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.qty' => 'required|integer|min:1',
        ]);

        $quantities = collect($validated['products'])
            ->groupBy('product_id')
            ->map(fn ($items) => $items->sum('qty'));

        $result = DB::transaction(function () use ($quantities) {
            $products = [];

            foreach ($quantities->sortKeys() as $productId => $qty) {
                $product = Product::lockForUpdate()->find($productId);

                if (!$product || $product->stock < $qty) {
                    return ['error' => $product
                        ? "Stok {$product->name} tidak mencukupi"
                        : 'Salah satu produk sudah tidak tersedia'];
                }

                $products[] = [$product, $qty];
            }

            $total = 0;
            $orderProducts = [];

            foreach ($products as [$product, $qty]) {
                $subtotal = $product->price * $qty;
                $total += $subtotal;
                $orderProducts[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'qty' => $qty,
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ];
            }

            foreach ($products as [$product, $qty]) {
                $product->decrement('stock', $qty);
            }

            Order::create([
                'user_id' => Auth::id(),
                'order_id' => 'ORD-' . strtoupper(Str::random(8)),
                'products' => $orderProducts,
                'total' => $total,
                'status' => 'diproses',
            ]);

            return ['success' => true];
        });

        if (isset($result['error'])) {
            return back()->with('error', $result['error']);
        }

        return redirect()->route('user.orders.index')
            ->with('success', 'Pesanan berhasil dibuat');
    }

    public function cancel(int $id)
    {
        $order = Order::where('user_id', Auth::id())
            ->findOrFail($id);

        if ($order->status !== 'diproses') {
            return back()->with('error', 'Pesanan hanya bisa dibatalkan jika statusnya diproses');
        }

        foreach ($order->products as $item) {
            $productId = $item['id'] ?? null;
            if ($productId) {
                $product = Product::find($productId);
                if ($product) {
                    $product->increment('stock', $item['qty']);
                }
            }
        }

        $order->update(['status' => 'dibatalkan']);

        return back()->with('success', 'Pesanan berhasil dibatalkan');
    }
}
