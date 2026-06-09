<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(): View
    {
        try {
            $stats = $this->dashboardService->getStats();
            $tasks = $this->dashboardService->getTasks();
        } catch (\Exception $e) {
            $stats = ['products' => 0, 'customers' => 0, 'orders' => 0, 'low_stock_count' => 0, 'expiring_count' => 0, 'revenue_month' => 0];
            $tasks = [];
        }

        return view('dashboard', compact('stats', 'tasks'));
    }

    public function getDashboardData(): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $this->dashboardService->getStats()]);
    }

    public function getLowStockAlerts(): JsonResponse
    {
        $items = Product::with(['category', 'stocks'])
            ->whereHas('stocks', fn ($q) => $q->where('quantity_stock', '<=', 10))
            ->limit(20)
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'stock' => $p->stocks->sum('quantity_stock'),
                'category' => $p->category?->name,
            ]);

        return response()->json(['success' => true, 'data' => $items]);
    }

    public function getSalesAnalytics(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->dashboardService->getSalesAnalytics(),
        ]);
    }

    public function getInventoryAnalytics(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->dashboardService->getInventoryAnalytics(),
        ]);
    }
}
