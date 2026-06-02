<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $todayTransactions = Transaction::whereDate('date', Carbon::today())->count();
        
        // Use raw query for field comparison if stock and min_stock are integer columns
        // Product::whereColumn('stock', '<=', 'min_stock')->get() is preferred in Laravel
        $lowStockProducts = Product::whereColumn('stock', '<=', 'min_stock')->get();
        
        $recentTransactions = Transaction::with('user')->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalProducts', 
            'totalCategories', 
            'todayTransactions', 
            'lowStockProducts', 
            'recentTransactions'
        ));
    }
}
