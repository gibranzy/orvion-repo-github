<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'selesai')->sum('total');
        
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalProducts',
            'totalOrders',
            'totalRevenue',
            'recentOrders'
        ));
    }

    public function analytics()
    {
        // Total statistics
        $totalProducts = Product::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'selesai')->sum('total');
        
        // Orders by status
        $ordersByStatus = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();
        
        // Recent orders
        $recentOrders = Order::with('user')->latest()->take(5)->get();
        
        // Top products (most stock available)
        $topProducts = Product::orderBy('stock', 'desc')->take(5)->get();
        
        // Revenue by month (last 6 months)
        $revenueByMonth = Order::where('status', 'selesai')
            ->selectRaw('MONTH(created_at) as month, SUM(total) as total')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        // Category distribution
        $categoryDistribution = Product::selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->orderByDesc('count')
            ->get();
        
        // Low stock products
        $lowStockProducts = Product::where('stock', '<=', 10)
            ->where('stock', '>', 0)
            ->take(5)
            ->get();
        
        // Out of stock products
        $outOfStockProducts = Product::where('stock', 0)->count();
        
        return view('admin.analytics', compact(
            'totalProducts',
            'totalUsers',
            'totalOrders',
            'totalRevenue',
            'ordersByStatus',
            'recentOrders',
            'topProducts',
            'revenueByMonth',
            'categoryDistribution',
            'lowStockProducts',
            'outOfStockProducts'
        ));
    }
}