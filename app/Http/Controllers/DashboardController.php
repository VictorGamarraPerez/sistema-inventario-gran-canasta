<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Entry;
use App\Models\ProductExit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total de productos en stock (suma de todos los stocks)
        $totalProductsInStock = Product::where('active', true)->sum('stock');
        
        // Productos con stock bajo (stock menor o igual a min_stock)
        $lowStockProducts = Product::where('active', true)
            ->whereColumn('stock', '<=', 'min_stock')
            ->count();
        
        // Entradas recientes (últimos 7 días)
        $recentEntries = Entry::where('entry_date', '>=', now()->subDays(7))
            ->count();
        
        // Salidas recientes (últimos 7 días)
        $recentExits = ProductExit::where('exit_date', '>=', now()->subDays(7))
            ->count();
        
        // Productos más recientes
        $latestProducts = Product::where('active', true)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Últimas entradas
        $latestEntries = Entry::with(['product', 'supplier'])
            ->orderBy('entry_date', 'desc')
            ->limit(5)
            ->get();
        
        // Últimas salidas
        $latestExits = ProductExit::with('product')
            ->orderBy('exit_date', 'desc')
            ->limit(5)
            ->get();
        
        return view('dashboard', compact(
            'totalProductsInStock',
            'lowStockProducts',
            'recentEntries',
            'recentExits',
            'latestProducts',
            'latestEntries',
            'latestExits'
        ));
    }
}
