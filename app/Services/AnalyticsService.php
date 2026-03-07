<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AnalyticsService
{
    /**
     * Get sales analytics for a date range
     */
    public function getSalesAnalytics($from = null, $to = null)
    {
        $from = $from ?? now()->subDays(30);
        $to = $to ?? now();
        
        return Cache::remember("sales_analytics_{$from->format('Y-m-d')}_{$to->format('Y-m-d')}", 300, function () use ($from, $to) {
            $orders = Order::whereBetween('created_at', [$from, $to])
                ->where('status', 'completed');
            
            return [
                'total_revenue' => $orders->sum('total'),
                'total_orders' => $orders->count(),
                'average_order_value' => $orders->avg('total'),
                'daily_sales' => $this->getDailySalesData($from, $to),
                'top_customers' => $this->getTopCustomers($from, $to, 5),
                'sales_by_category' => $this->getSalesByCategory($from, $to),
                'monthly_trend' => $this->getMonthlySalesTrend(12),
            ];
        });
    }

    /**
     * Get daily sales data for charts
     */
    private function getDailySalesData($from, $to)
    {
        return Order::whereBetween('created_at', [$from, $to])
            ->where('status', 'completed')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(total) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'orders' => $item->orders,
                    'revenue' => (float) $item->revenue,
                ];
            });
    }

    /**
     * Get top customers by revenue
     */
    private function getTopCustomers($from, $to, $limit = 5)
    {
        return Order::whereBetween('created_at', [$from, $to])
            ->where('status', 'completed')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->selectRaw('customers.*, COUNT(orders.id) as order_count, SUM(orders.total) as total_spent')
            ->groupBy('customers.id')
            ->orderBy('total_spent', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get sales breakdown by category
     */
    private function getSalesByCategory($from, $to)
    {
        return DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereBetween('orders.created_at', [$from, $to])
            ->where('orders.status', 'completed')
            ->selectRaw('categories.name, COUNT(order_items.id) as items_sold, SUM(order_items.quantity * order_items.price) as revenue')
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('revenue', 'desc')
            ->get();
    }

    /**
     * Get monthly sales trend
     */
    private function getMonthlySalesTrend($months = 12)
    {
        return Order::where('created_at', '>=', now()->subMonths($months))
            ->where('status', 'completed')
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as orders, SUM(total) as revenue')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => date('Y-m', mktime(0, 0, 0, $item->month, 1, $item->year)),
                    'orders' => $item->orders,
                    'revenue' => (float) $item->revenue,
                ];
            });
    }

    /**
     * Get inventory analytics
     */
    public function getInventoryAnalytics()
    {
        return Cache::remember('inventory_analytics', 600, function () {
            return [
                'total_value' => Product::join('stocks', 'products.id', '=', 'stocks.product_id')
                    ->sum(DB::raw('stocks.quantity_stock * products.price')),
                'stock_distribution' => $this->getStockDistribution(),
                'category_distribution' => $this->getCategoryDistribution(),
                'supplier_performance' => $this->getSupplierPerformance(),
                'low_stock_value' => $this->getLowStockValue(),
                'expiring_value' => $this->getExpiringValue(),
            ];
        });
    }

    /**
     * Get stock distribution (in stock, low stock, out of stock)
     */
    private function getStockDistribution()
    {
        $products = Product::with('stocks')->get();
        
        $inStock = 0;
        $lowStock = 0;
        $outOfStock = 0;
        
        foreach ($products as $product) {
            $currentStock = $product->stocks->sum('quantity_stock');
            
            if ($currentStock == 0) {
                $outOfStock++;
            } elseif ($currentStock <= $product->min_stock) {
                $lowStock++;
            } else {
                $inStock++;
            }
        }
        
        return [
            'in_stock' => $inStock,
            'low_stock' => $lowStock,
            'out_of_stock' => $outOfStock,
            'total' => $products->count(),
        ];
    }

    /**
     * Get medication distribution by category
     */
    private function getCategoryDistribution()
    {
        return Product::join('categories', 'products.category_id', '=', 'categories.id')
            ->selectRaw('categories.name, COUNT(products.id) as count, AVG(products.price) as avg_price')
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('count', 'desc')
            ->get();
    }

    /**
     * Get supplier performance metrics
     */
    private function getSupplierPerformance()
    {
        return DB::table('products')
            ->join('suppliers', 'products.supplier_id', '=', 'suppliers.id')
            ->join('stocks', 'products.id', '=', 'stocks.product_id')
            ->selectRaw('suppliers.name, COUNT(products.id) as products, SUM(stocks.quantity_stock * products.price) as inventory_value')
            ->groupBy('suppliers.id', 'suppliers.name')
            ->orderBy('inventory_value', 'desc')
            ->get();
    }

    /**
     * Get value of low stock items
     */
    private function getLowStockValue()
    {
        return Product::join('stocks', 'products.id', '=', 'stocks.product_id')
            ->whereColumn('stocks.quantity_stock', '<=', 'products.min_stock')
            ->sum(DB::raw('stocks.quantity_stock * products.price'));
    }

    /**
     * Get value of expiring items
     */
    private function getExpiringValue()
    {
        return Product::join('stocks', 'products.id', '=', 'stocks.product_id')
            ->where('track_expiry', true)
            ->whereNotNull('expiry_date')
            ->whereBetween('expiry_date', [now(), now()->addDays(30)])
            ->sum(DB::raw('stocks.quantity_stock * products.price'));
    }

    /**
     * Get top selling medications
     */
    public function getTopSellingMedications($limit = 10, $days = 30)
    {
        return Cache::remember("top_selling_{$limit}_{$days}", 600, function () use ($limit, $days) {
            return DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('orders.created_at', '>=', now()->subDays($days))
                ->where('orders.status', 'completed')
                ->selectRaw('products.*, SUM(order_items.quantity) as total_sold, SUM(order_items.quantity * order_items.price) as revenue')
                ->groupBy('products.id')
                ->orderBy('total_sold', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get customer analytics
     */
    public function getCustomerAnalytics($days = 30)
    {
        return Cache::remember("customer_analytics_{$days}", 300, function () use ($days) {
            $from = now()->subDays($days);
            
            return [
                'new_customers' => Customer::where('created_at', '>=', $from)->count(),
                'active_customers' => Order::where('created_at', '>=', $from)
                    ->distinct('customer_id')
                    ->count(),
                'repeat_customers' => Customer::whereHas('orders', function ($query) use ($from) {
                    $query->where('created_at', '>=', $from);
                })->count(),
                'customer_growth' => $this->getCustomerGrowthTrend(6),
            ];
        });
    }

    /**
     * Get customer growth trend
     */
    private function getCustomerGrowthTrend($months = 6)
    {
        return Customer::where('created_at', '>=', now()->subMonths($months))
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as new_customers')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => date('Y-m', mktime(0, 0, 0, $item->month, 1, $item->year)),
                    'new_customers' => $item->new_customers,
                ];
            });
    }

    /**
     * Get financial analytics
     */
    public function getFinancialAnalytics($from = null, $to = null)
    {
        $from = $from ?? now()->subDays(30);
        $to = $to ?? now();
        
        return Cache::remember("financial_analytics_{$from->format('Y-m-d')}_{$to->format('Y-m-d')}", 300, function () use ($from, $to) {
            return [
                'revenue' => Order::whereBetween('created_at', [$from, $to])
                    ->where('status', 'completed')
                    ->sum('total'),
                'cost_of_goods' => $this->getCostOfGoodsSold($from, $to),
                'gross_profit' => 0, // Will be calculated after COGS
                'profit_margin' => 0, // Will be calculated after COGS
                'revenue_by_payment_method' => $this->getRevenueByPaymentMethod($from, $to),
                'cash_flow' => $this->getCashFlowData($from, $to),
            ];
        });
    }

    /**
     * Get cost of goods sold
     */
    private function getCostOfGoodsSold($from, $to)
    {
        // This would need to be implemented based on your cost tracking
        // For now, returning a placeholder
        return Order::whereBetween('created_at', [$from, $to])
            ->where('status', 'completed')
            ->sum('total') * 0.7; // Assuming 70% COGS
    }

    /**
     * Get revenue by payment method
     */
    private function getRevenueByPaymentMethod($from, $to)
    {
        return Order::whereBetween('created_at', [$from, $to])
            ->where('status', 'completed')
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total) as revenue')
            ->groupBy('payment_method')
            ->get();
    }

    /**
     * Get cash flow data
     */
    private function getCashFlowData($from, $to)
    {
        return [
            'cash_in' => Order::whereBetween('created_at', [$from, $to])
                ->where('status', 'completed')
                ->where('payment_method', 'cash')
                ->sum('total'),
            'card_in' => Order::whereBetween('created_at', [$from, $to])
                ->where('status', 'completed')
                ->where('payment_method', 'card')
                ->sum('total'),
            'other_in' => Order::whereBetween('created_at', [$from, $to])
                ->where('status', 'completed')
                ->whereNotIn('payment_method', ['cash', 'card'])
                ->sum('total'),
        ];
    }

    /**
     * Get dashboard summary data
     */
    public function getDashboardSummary()
    {
        return Cache::remember('dashboard_summary', 300, function () {
            $today = now();
            $yesterday = now()->subDay();
            $lastWeek = now()->subWeek();
            $lastMonth = now()->subMonth();
            
            return [
                'today_stats' => [
                    'revenue' => Order::whereDate('created_at', $today)
                        ->where('status', 'completed')
                        ->sum('total'),
                    'orders' => Order::whereDate('created_at', $today)->count(),
                    'customers' => Customer::whereDate('created_at', $today)->count(),
                ],
                'yesterday_stats' => [
                    'revenue' => Order::whereDate('created_at', $yesterday)
                        ->where('status', 'completed')
                        ->sum('total'),
                    'orders' => Order::whereDate('created_at', $yesterday)->count(),
                    'customers' => Customer::whereDate('created_at', $yesterday)->count(),
                ],
                'this_week' => [
                    'revenue' => Order::whereBetween('created_at', [$lastWeek, $today])
                        ->where('status', 'completed')
                        ->sum('total'),
                    'orders' => Order::whereBetween('created_at', [$lastWeek, $today])->count(),
                ],
                'this_month' => [
                    'revenue' => Order::whereBetween('created_at', [$lastMonth, $today])
                        ->where('status', 'completed')
                        ->sum('total'),
                    'orders' => Order::whereBetween('created_at', [$lastMonth, $today])->count(),
                ],
                'quick_stats' => [
                    'low_stock_count' => \App\Models\Product::whereHas('stocks', function ($query) {
                        $query->whereColumn('quantity_stock', '<=', 'products.min_stock');
                    })->count(),
                    'expiring_soon' => \App\Models\Product::where('track_expiry', true)
                        ->whereNotNull('expiry_date')
                        ->whereBetween('expiry_date', [now(), now()->addDays(30)])
                        ->count(),
                    'out_of_stock' => \App\Models\Product::whereHas('stocks', function ($query) {
                        $query->where('quantity_stock', '=', 0);
                    })->count(),
                ],
            ];
        });
    }

    /**
     * Clear analytics caches
     */
    public function clearAnalyticsCache()
    {
        Cache::forget('dashboard_summary');
        Cache::forget('inventory_analytics');
        Cache::forget('customer_analytics_30');
        // Add more cache keys as needed
    }
}
