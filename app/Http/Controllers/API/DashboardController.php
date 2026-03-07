<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics.
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'products' => Product::count(),
            'customers' => \App\Models\Customer::count(),
            'suppliers' => \App\Models\Supplier::count(),
            'categories' => \App\Models\Category::count(),
            'orders' => Order::count(),
            'total_stock' => \App\Models\Stock::sum('quantity_stock'),
            'low_stock_count' => Product::lowStock()->count(),
            'expiring_soon_count' => Product::expiringSoon()->count(),
            'expired_count' => Product::expired()->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Dashboard statistics retrieved successfully'
        ]);
    }

    /**
     * Get low stock products.
     */
    public function lowStock(): JsonResponse
    {
        $products = Product::with(['category', 'supplier', 'stocks'])
            ->lowStock()
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products,
            'message' => 'Low stock products retrieved successfully'
        ]);
    }

    /**
     * Get sales report data.
     */
    public function salesReport(Request $request): JsonResponse
    {
        $query = Order::with(['customer', 'products']);

        // Date range filter
        if ($request->has('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        // Calculate statistics
        $totalSales = $orders->sum(function ($order) {
            return $order->products->sum(function ($product) {
                return $product->pivot->price * $product->pivot->quantity;
            });
        });

        $totalOrders = $orders->count();
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        // Group by month for chart
        $monthlySales = $orders->groupBy(function ($order) {
            return $order->created_at->format('Y-m');
        })->map(function ($monthOrders) {
            return $monthOrders->sum(function ($order) {
                return $order->products->sum(function ($product) {
                    return $product->pivot->price * $product->pivot->quantity;
                });
            });
        });

        return response()->json([
            'success' => true,
            'data' => [
                'total_sales' => $totalSales,
                'total_orders' => $totalOrders,
                'average_order_value' => $averageOrderValue,
                'monthly_sales' => $monthlySales,
                'orders' => $orders,
            ],
            'message' => 'Sales report retrieved successfully'
        ]);
    }

    /**
     * Get inventory report data.
     */
    public function inventoryReport(): JsonResponse
    {
        $products = Product::with(['category', 'supplier', 'stocks'])->get();

        $totalValue = $products->sum(function ($product) {
            return ($product->price ?? 0) * $product->current_stock;
        });

        $totalStock = $products->sum('current_stock');
        $lowStockCount = $products->filter(fn($p) => $p->is_low_stock)->count();
        $expiredCount = $products->filter(fn($p) => $p->is_expired)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'products' => $products,
                'total_value' => $totalValue,
                'total_stock' => $totalStock,
                'low_stock_count' => $lowStockCount,
                'expired_count' => $expiredCount,
            ],
            'message' => 'Inventory report retrieved successfully'
        ]);
    }

    /**
     * Get expiry report data.
     */
    public function expiryReport(Request $request): JsonResponse
    {
        $query = Product::with(['category', 'supplier'])
            ->where('track_expiry', true)
            ->whereNotNull('expiry_date');

        // Filter by expiry period
        if ($request->has('days')) {
            $query->where('expiry_date', '<=', now()->addDays($request->days));
        }

        $products = $query->orderBy('expiry_date')->get();

        // Group by expiry status
        $expired = $products->filter(fn($p) => $p->is_expired);
        $expiringSoon = $products->filter(fn($p) => $p->is_expiring_soon && !$p->is_expired);

        $valueAtRisk = $expired->concat($expiringSoon)->sum(function ($product) {
            return ($product->price ?? 0) * $product->current_stock;
        });

        return response()->json([
            'success' => true,
            'data' => [
                'products' => $products,
                'expired_count' => $expired->count(),
                'expiring_soon_count' => $expiringSoon->count(),
                'value_at_risk' => $valueAtRisk,
            ],
            'message' => 'Expiry report retrieved successfully'
        ]);
    }
}
