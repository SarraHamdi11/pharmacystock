<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Cache;

class DashboardService
{
    public function getStats(): array
    {
        return Cache::remember('dashboard_stats', 300, function () {
            $revenueMonth = (float) DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereMonth('orders.created_at', now()->month)
                ->whereYear('orders.created_at', now()->year)
                ->selectRaw('SUM(order_items.price * order_items.quantity) as total')
                ->value('total') ?? 0;

            return [
                'products' => Product::count(),
                'customers' => Customer::count(),
                'orders' => Order::whereMonth('created_at', now()->month)->count(),
                'low_stock_count' => Stock::where('quantity_stock', '<=', 10)->count(),
                'expiring_count' => Product::where('track_expiry', true)
                    ->whereNotNull('expiry_date')
                    ->whereBetween('expiry_date', [now(), now()->addDays(30)])
                    ->count(),
                'revenue_month' => $revenueMonth,
            ];
        });
    }

    public function getTasks(): array
    {
        return Cache::remember('dashboard_tasks', 300, function () {
            $tasks = [];

            $lowStock = Product::with('stocks')
                ->whereHas('stocks', fn ($q) => $q->where('quantity_stock', '<=', 10))
                ->limit(3)
                ->get();

            foreach ($lowStock as $product) {
                $tasks[] = [
                    'title' => "Restock {$product->name}",
                    'detail' => ($product->stocks->sum('quantity_stock')).' units left',
                    'urgency' => 'high',
                    'url' => route('products.edit', $product->id),
                ];
            }

            $expiring = Product::where('track_expiry', true)
                ->whereNotNull('expiry_date')
                ->whereBetween('expiry_date', [now(), now()->addDays(14)])
                ->orderBy('expiry_date')
                ->limit(3)
                ->get();

            foreach ($expiring as $product) {
                $tasks[] = [
                    'title' => "Check expiry: {$product->name}",
                    'detail' => 'Expires '.$product->expiry_date->format('M j, Y'),
                    'urgency' => 'medium',
                    'url' => route('products.edit', $product->id),
                ];
            }

            if (empty($tasks)) {
                $tasks[] = [
                    'title' => 'All inventory healthy',
                    'detail' => 'No urgent actions needed',
                    'urgency' => 'low',
                    'url' => route('products.index'),
                ];
            }

            return $tasks;
        });
    }

    public function getSalesAnalytics(int $daysLimit = 7): array
    {
        return Cache::remember("sales_analytics_{$daysLimit}", 300, function () use ($daysLimit) {
            $days = collect(range($daysLimit - 1, 0))->map(fn ($i) => now()->subDays($i)->format('Y-m-d'));

            $sales = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.created_at', '>=', now()->subDays($daysLimit - 1)->startOfDay())
                ->selectRaw('DATE(orders.created_at) as date, SUM(order_items.price * order_items.quantity) as revenue')
                ->groupBy('date')
                ->pluck('revenue', 'date');

            return [
                'labels' => $days->map(fn ($d) => now()->parse($d)->format('D'))->values(),
                'revenue' => $days->map(fn ($d) => round((float) ($sales[$d] ?? 0), 2))->values(),
            ];
        });
    }

    public function getInventoryAnalytics(): array
    {
        return Cache::remember('inventory_analytics', 600, function () {
            $stats = DB::table('products')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->join('stocks', 'products.id', '=', 'stocks.product_id')
                ->select('categories.name as category_name', DB::raw('SUM(stocks.quantity_stock) as total_stock'))
                ->groupBy('categories.name')
                ->orderByDesc('total_stock')
                ->take(6)
                ->get();

            return [
                'labels' => $stats->pluck('category_name'),
                'values' => $stats->pluck('total_stock')->map(fn($v) => (int)$v),
            ];
        });
    }
}
