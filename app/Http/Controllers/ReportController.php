<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Order;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Show the reports dashboard.
     */
    public function index(): View
    {
        return view('reports.index');
    }

    /**
     * Generate inventory report.
     */
    public function inventory(Request $request): View
    {
        $query = Product::with(['category', 'supplier', 'stocks']);

        // Apply filters
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        if ($request->filled('stock_status')) {
            if ($request->stock_status == 'low') {
                $query->whereHas('stocks', function ($q) {
                    $q->where('quantity_stock', '<=', \DB::raw('min_stock'));
                });
            } elseif ($request->stock_status == 'out') {
                $query->whereHas('stocks', function ($q) {
                    $q->where('quantity_stock', '=', 0);
                });
            }
        }

        $products = $query->get();
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('reports.inventory', compact('products', 'categories', 'suppliers'));
    }

    /**
     * Generate inventory report (alias for route compatibility).
     */
    public function inventoryReport(Request $request): View
    {
        return $this->inventory($request);
    }

    /**
     * Generate sales report.
     */
    public function sales(Request $request): View
    {
        try {
            $query = Order::with(['customer']);

            // Date range filter
            if ($request->has('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }

            if ($request->has('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }

            if ($request->filled('customer_id')) {
                $query->where('customer_id', $request->customer_id);
            }

            $orders = $query->orderBy('created_at', 'desc')->get();

            // Calculate statistics (simplified without product relationships)
            $totalSales = $orders->sum('total') ?? 0;
            $totalOrders = $orders->count();
            $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

            // Group by month for chart
            $monthlySales = $orders->groupBy(function ($order) {
                return $order->created_at->format('Y-m');
            })->map(function ($monthOrders) {
                return $monthOrders->sum('total') ?? 0;
            });

            $customers = \App\Models\Customer::all();

            return view('reports.sales', compact(
                'orders',
                'customers',
                'totalSales',
                'totalOrders',
                'averageOrderValue',
                'monthlySales'
            ));
        } catch (\Exception $e) {
            // Fallback if there are database issues
            $orders = collect([]);
            $customers = \App\Models\Customer::all();
            $totalSales = 0;
            $totalOrders = 0;
            $averageOrderValue = 0;
            $monthlySales = collect([]);
            
            return view('reports.sales', compact(
                'orders',
                'customers',
                'totalSales',
                'totalOrders',
                'averageOrderValue',
                'monthlySales'
            ));
        }
    }

    /**
     * Generate sales report (alias for route compatibility).
     */
    public function salesReport(Request $request): View
    {
        return $this->sales($request);
    }

    /**
     * Generate expiry report.
     */
    public function expiry(Request $request): View
    {
        $query = Product::with(['category', 'supplier'])
            ->where('track_expiry', true)
            ->whereNotNull('expiry_date');

        // Filter by expiry period
        if ($request->has('expiry_period')) {
            $days = match($request->expiry_period) {
                '7' => 7,
                '30' => 30,
                '60' => 60,
                '90' => 90,
                default => 30
            };
            $query->where('expiry_date', '<=', now()->addDays($days));
        }

        $products = $query->orderBy('expiry_date')->get();

        // Group by expiry status
        $expired = $products->filter(fn($p) => $p->is_expired);
        $expiringSoon = $products->filter(fn($p) => $p->is_expiring_soon && !$p->is_expired);
        $good = $products->filter(fn($p) => !$p->is_expired && !$p->is_expiring_soon);

        // Calculate value at risk
        $valueAtRisk = $expired->concat($expiringSoon)->sum(function ($product) {
            return ($product->price ?? 0) * $product->current_stock;
        });

        return view('reports.expiry', compact(
            'products',
            'expired',
            'expiringSoon',
            'good',
            'valueAtRisk'
        ));
    }

    /**
     * Generate supplier report.
     */
    public function supplierReport(Request $request): View
    {
        try {
            $query = Supplier::with(['products']);

            // Apply filters
            if ($request->filled('category_id')) {
                $query->whereHas('products', function ($q) use ($request) {
                    $q->where('category_id', $request->category_id);
                });
            }

            $suppliers = $query->get();
            $categories = Category::all();

            return view('reports.suppliers', compact('suppliers', 'categories'));
        } catch (\Exception $e) {
            // Fallback if there are database issues
            $suppliers = collect([]);
            $categories = Category::all();
            
            return view('reports.suppliers', compact('suppliers', 'categories'));
        }
    }

    /**
     * Generate customer report.
     */
    public function customerReport(Request $request): View
    {
        try {
            $query = \App\Models\Customer::with(['orders']);

            // Apply filters
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $customers = $query->get();

            return view('reports.customers', compact('customers'));
        } catch (\Exception $e) {
            // Fallback if there are database issues
            $customers = collect([]);
            
            return view('reports.customers', compact('customers'));
        }
    }

    /**
     * Export inventory report to Excel.
     */
    public function exportInventory(Request $request)
    {
        $query = Product::with(['category', 'supplier', 'stocks']);

        // Apply same filters as inventory report
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $products = $query->get();

        $exportData = $products->map(function ($product) {
            return [
                'ID' => $product->id,
                'Name' => $product->name,
                'Generic Name' => $product->generic_name,
                'Barcode' => $product->code_bar,
                'Category' => $product->category->name ?? 'N/A',
                'Supplier' => $product->supplier->name ?? 'N/A',
                'Current Stock' => $product->current_stock,
                'Min Stock' => $product->min_stock ?? 10,
                'Price' => $product->price ?? 0,
                'Total Value' => ($product->price ?? 0) * $product->current_stock,
                'Expiry Date' => $product->expiry_date?->format('Y-m-d') ?? 'N/A',
                'Status' => $product->stock_status,
                'Batch Number' => $product->batch_number,
                'Manufacturer' => $product->manufacturer,
            ];
        });

        return Excel::download(new class($exportData->toArray()) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
            private $data;
            
            public function __construct(array $data)
            {
                $this->data = $data;
            }
            
            public function collection()
            {
                return collect($this->data);
            }
            
            public function headings(): array
            {
                return array_keys($this->data[0] ?? []);
            }
        }, 'inventory_report_' . now()->format('Y-m-d') . '.xlsx');
    }

    /**
     * Export sales report to Excel.
     */
    public function exportSales(Request $request)
    {
        $query = Order::with(['customer', 'products']);

        if ($request->has('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $orders = $query->get();

        $exportData = $orders->flatMap(function ($order) {
            return $order->products->map(function ($product) use ($order) {
                return [
                    'Order ID' => $order->id,
                    'Date' => $order->created_at->format('Y-m-d H:i:s'),
                    'Customer' => $order->customer->name ?? 'N/A',
                    'Product Name' => $product->name,
                    'Quantity' => $product->pivot->quantity,
                    'Unit Price' => $product->pivot->price,
                    'Total' => $product->pivot->price * $product->pivot->quantity,
                ];
            });
        });

        return Excel::download(new class($exportData->toArray()) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
            private $data;
            
            public function __construct(array $data)
            {
                $this->data = $data;
            }
            
            public function collection()
            {
                return collect($this->data);
            }
            
            public function headings(): array
            {
                return array_keys($this->data[0] ?? []);
            }
        }, 'sales_report_' . now()->format('Y-m-d') . '.xlsx');
    }
}
