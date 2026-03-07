<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard
     */
    public function index(): View
    {
        // Set session to show loading screen on first visit
        if (!session()->has('dashboard_visited')) {
            session(['show_loading' => true, 'dashboard_visited' => true]);
        } else {
            session()->forget('show_loading');
        }
        
        try {
            // Get basic statistics
            $stats = [
                'products' => \App\Models\Product::count(),
                'customers' => \App\Models\Customer::count(),
                'orders' => \App\Models\Order::whereMonth('created_at', now()->month)->count(),
                'low_stock_count' => \App\Models\Stock::where('quantity_stock', '<=', 10)->count(),
            ];
            
            return view('dashboard', compact('stats'));
            
        } catch (\Exception $e) {
            // Fallback stats if there's an error
            $stats = [
                'products' => 0,
                'customers' => 0,
                'orders' => 0,
                'low_stock_count' => 0,
            ];
            
            return view('dashboard', compact('stats'));
        }
    }

    /**
     * Get dashboard data for AJAX requests
     */
    public function getDashboardData(): JsonResponse
    {
        try {
            $stats = [
                'products' => \App\Models\Product::count(),
                'customers' => \App\Models\Customer::count(),
                'orders' => \App\Models\Order::whereMonth('created_at', now()->month)->count(),
                'low_stock_count' => \App\Models\Stock::where('quantity_stock', '<=', 10)->count(),
            ];
            
            return response()->json(['success' => true, 'data' => $stats]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // Keep existing methods that are used by routes - redirect to main views
    public function customers()
    {
        return redirect()->route('customers.index');
    }

    public function suppliers()
    {
        return redirect()->route('suppliers.index');
    }

    public function orders()
    {
        return redirect()->route('orders.index');
    }

    public function maladies()
    {
        // Redirect to dashboard since maladies view doesn't exist
        return redirect()->route('dashboard.index');
    }

    public function productsBySupplier()
    {
        return redirect()->route('products.index');
    }

    public function productsByStore()
    {
        return redirect()->route('products.index');
    }
}
