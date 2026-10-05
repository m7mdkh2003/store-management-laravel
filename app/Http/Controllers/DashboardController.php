<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $productsCount = Product::count();
        $categoriesCount = Category::count();
        $ordersCount = Order::count();
        $totalSales = Order::where('status', Order::STATUS_COMPLETED)->sum('total_price');
        $pendingOrders = Order::where('status', Order::STATUS_PENDING)->count();
        $completedOrders = Order::where('status', Order::STATUS_COMPLETED)->count();
        $lowStockProducts = Product::where('stock', '<=', 5)->count();

        $latestOrders = Order::with(['user', 'product'])
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard', compact(
            'productsCount',
            'categoriesCount',
            'ordersCount',
            'totalSales',
            'pendingOrders',
            'completedOrders',
            'lowStockProducts',
            'latestOrders'
        ));
    }
}
