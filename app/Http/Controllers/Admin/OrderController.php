<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:diproses,dikirim,selesai,dibatalkan'
        ]);

        $oldStatus = $order->status;
        $newStatus = $validated['status'];

        if ($oldStatus === $newStatus) {
            return back()->with('success', 'Status pesanan diperbarui');
        }

        DB::transaction(function () use ($order, $oldStatus, $newStatus) {
            // Jika membatalkan pesanan dari status selain dibatalkan, kembalikan stok
            if ($newStatus === 'dibatalkan' && $oldStatus !== 'dibatalkan') {
                foreach ($order->products as $item) {
                    $productId = $item['id'] ?? null;
                    if ($productId) {
                        $product = Product::find($productId);
                        if ($product) {
                            $product->increment('stock', $item['qty']);
                        }
                    }
                }
            }
            // Jika pesanan yang tadinya dibatalkan diaktifkan kembali, kurangi stok jika mencukupi
            elseif ($oldStatus === 'dibatalkan' && $newStatus !== 'dibatalkan') {
                foreach ($order->products as $item) {
                    $productId = $item['id'] ?? null;
                    if ($productId) {
                        $product = Product::find($productId);
                        if ($product) {
                            if ($product->stock < $item['qty']) {
                                throw new \Exception("Stok untuk produk {$product->name} tidak mencukupi jika pesanan ini diaktifkan kembali.");
                            }
                            $product->decrement('stock', $item['qty']);
                        }
                    }
                }
            }

            $order->update(['status' => $newStatus]);
        });

        return back()->with('success', 'Status pesanan berhasil diperbarui');
    }
}
