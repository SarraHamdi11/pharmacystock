<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockAlert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class MedicationService
{
    /**
     * Get medications with low stock levels
     */
    public function getLowStockMedicines($threshold = null)
    {
        $threshold = $threshold ?? config('pharmacy.low_stock_threshold', 10);
        
        return Cache::remember('low_stock_medications', 300, function () use ($threshold) {
            return Product::whereHas('stocks', function ($query) use ($threshold) {
                    $query->whereColumn('quantity_stock', '<=', 'products.min_stock');
                })
                ->orWhereHas('stocks', function ($query) use ($threshold) {
                    $query->where('quantity_stock', '<=', $threshold);
                })
                ->with(['category', 'supplier', 'stocks'])
                ->get();
        });
    }

    /**
     * Get medications expiring soon
     */
    public function getExpiringMedicines($days = 30)
    {
        return Cache::remember("expiring_medications_{$days}", 600, function () use ($days) {
            return Product::where('track_expiry', true)
                ->whereNotNull('expiry_date')
                ->whereBetween('expiry_date', [now(), now()->addDays($days)])
                ->with(['category', 'supplier', 'stocks'])
                ->get();
        });
    }

    /**
     * Get expired medications
     */
    public function getExpiredMedications()
    {
        return Cache::remember('expired_medications', 600, function () {
            return Product::where('track_expiry', true)
                ->whereNotNull('expiry_date')
                ->where('expiry_date', '<', now())
                ->with(['category', 'supplier', 'stocks'])
                ->get();
        });
    }

    /**
     * Calculate total inventory value
     */
    public function getInventoryValue()
    {
        return Cache::remember('inventory_value', 600, function () {
            return Product::join('stocks', 'products.id', '=', 'stocks.product_id')
                ->sum(DB::raw('stocks.quantity_stock * products.price'));
        });
    }

    /**
     * Update stock levels with audit trail
     */
    public function updateStock($medicationId, $quantity, $type = 'add', $reason = null)
    {
        return DB::transaction(function () use ($medicationId, $quantity, $type, $reason) {
            $medication = Product::findOrFail($medicationId);

            $currentStock = $medication->stocks()->sum('quantity_stock') ?? 0;
            
            if ($type === 'subtract') {
                $quantity = min($quantity, $currentStock);
                $newStock = $currentStock - $quantity;
            } else {
                $newStock = $currentStock + $quantity;
            }

            $medication->stocks()->updateOrCreate(
                ['product_id' => $medicationId],
                ['quantity_stock' => $newStock]
            );

            if ($newStock <= $medication->min_stock) {
                StockAlert::createAlert(
                    $medicationId,
                    'low_stock',
                    "Stock level is critically low: {$newStock} units remaining",
                    $newStock,
                    $medication->min_stock
                );
            }

            activity()
                ->causedBy(auth()->user())
                ->performedOn($medication)
                ->withProperties([
                    'old_stock' => $currentStock,
                    'new_stock' => $newStock,
                    'quantity' => $quantity,
                    'type' => $type,
                    'reason' => $reason,
                ])
                ->log("Stock {$type}ed: {$quantity} units");

            $this->clearStockCaches();

            return $medication->fresh(['stocks']);
        });
    }

    /**
     * Get medication statistics
     */
    public function getMedicationStats()
    {
        return Cache::remember('medication_stats', 300, function () {
            return [
                'total_medications' => Product::count(),
                'low_stock_count' => $this->getLowStockMedicines()->count(),
                'expiring_soon_count' => $this->getExpiringMedicines()->count(),
                'expired_count' => $this->getExpiredMedications()->count(),
                'total_value' => $this->getInventoryValue(),
                'categories_count' => \App\Models\Category::count(),
                'suppliers_count' => \App\Models\Supplier::count(),
            ];
        });
    }

    /**
     * Search medications with filters
     */
    public function searchMedications($query, $filters = [])
    {
        $medications = Product::with(['category', 'supplier', 'stocks']);

        if ($query) {
            $medications->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('generic_name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('barcode', 'like', "%{$query}%")
                  ->orWhere('manufacturer', 'like', "%{$query}%");
            });
        }

        if (isset($filters['category_id'])) {
            $medications->where('category_id', $filters['category_id']);
        }

        if (isset($filters['supplier_id'])) {
            $medications->where('supplier_id', $filters['supplier_id']);
        }

        if (isset($filters['min_price'])) {
            $medications->where('price', '>=', $filters['min_price']);
        }

        if (isset($filters['max_price'])) {
            $medications->where('price', '<=', $filters['max_price']);
        }

        if (isset($filters['stock_status'])) {
            switch ($filters['stock_status']) {
                case 'low':
                    $medications->whereHas('stocks', function ($q) {
                        $q->whereColumn('quantity_stock', '<=', 'products.min_stock');
                    });
                    break;
                case 'out':
                    $medications->whereHas('stocks', function ($q) {
                        $q->where('quantity_stock', '=', 0);
                    });
                    break;
                case 'available':
                    $medications->whereHas('stocks', function ($q) {
                        $q->where('quantity_stock', '>', 0);
                    });
                    break;
            }
        }

        if (isset($filters['expiry_status'])) {
            switch ($filters['expiry_status']) {
                case 'expiring':
                    $medications->where('track_expiry', true)
                        ->whereNotNull('expiry_date')
                        ->whereBetween('expiry_date', [now(), now()->addDays(30)]);
                    break;
                case 'expired':
                    $medications->where('track_expiry', true)
                        ->whereNotNull('expiry_date')
                        ->where('expiry_date', '<', now());
                    break;
            }
        }

        return $medications->paginate(25);
    }

    /**
     * Bulk update medications
     */
    public function bulkUpdateMedications($medicationIds, $data)
    {
        return DB::transaction(function () use ($medicationIds, $data) {
            $updated = Product::whereIn('id', $medicationIds)->update($data);

            activity()
                ->causedBy(auth()->user())
                ->withProperties([
                    'medication_ids' => $medicationIds,
                    'updated_data' => $data,
                    'updated_count' => $updated,
                ])
                ->log("Bulk updated {$updated} medications");

            $this->clearStockCaches();

            return $updated;
        });
    }

    /**
     * Get top selling medications
     */
    public function getTopSellingMedications($limit = 10, $days = 30)
    {
        return Cache::remember("top_selling_{$limit}_{$days}", 600, function () use ($limit, $days) {
            return Product::join('order_items', 'products.id', '=', 'order_items.product_id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.created_at', '>=', now()->subDays($days))
                ->where('orders.status', 'completed')
                ->groupBy('products.id')
                ->selectRaw('products.*, SUM(order_items.quantity) as total_sold')
                ->orderBy('total_sold', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Export medications to CSV
     */
    public function exportToCSV($filters = [])
    {
        $medications = $this->searchMedications('', $filters);
        
        $filename = 'medications_' . date('Y-m-d_H-i-s') . '.csv';
        $path = 'exports/' . $filename;
        
        $csv = fopen(storage_path('app/' . $path), 'w');
        
        // Header
        fputcsv($csv, [
            'ID', 'Name', 'Generic Name', 'Category', 'Supplier', 
            'Price', 'Stock', 'Min Stock', 'Barcode', 'Expiry Date', 
            'Manufacturer', 'Created At'
        ]);
        
        // Data
        foreach ($medications as $medication) {
            fputcsv($csv, [
                $medication->id,
                $medication->name,
                $medication->generic_name,
                $medication->category->name ?? 'N/A',
                $medication->supplier->name ?? 'N/A',
                $medication->price,
                $medication->current_stock,
                $medication->min_stock,
                $medication->barcode,
                $medication->expiry_date?->format('Y-m-d'),
                $medication->manufacturer,
                $medication->created_at->format('Y-m-d H:i:s')
            ]);
        }
        
        fclose($csv);
        
        return $path;
    }

    /**
     * Clear stock-related caches
     */
    private function clearStockCaches()
    {
        Cache::forget('low_stock_medications');
        Cache::forget('expiring_medications_30');
        Cache::forget('expiring_medications_60');
        Cache::forget('expired_medications');
        Cache::forget('inventory_value');
        Cache::forget('medication_stats');
    }

    public function getMedicationByBarcode($barcode)
    {
        return Product::where('barcode', $barcode)
            ->with(['category', 'supplier', 'stocks'])
            ->first();
    }
}
