<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'generic_name',
        'code_bar',
        'category_id',
        'supplier_id',
        'description',
        'price',
        'min_stock',
        'expiry_date',
        'track_expiry',
        'storage_conditions',
        'batch_number',
        'manufacturer',
        'picture',
        'requires_prescription',
        'controlled_substance',
        'age_restricted',
        'storage_temperature',
        'shelf_life',
        'unit_of_measurement',
        'package_size',
        'cost_price',
        'markup_percentage',
        'discount_allowed',
        'max_discount_percentage',
        'active',
        'notes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'markup_percentage' => 'decimal:2',
        'max_discount_percentage' => 'decimal:2',
        'min_stock' => 'integer',
        'expiry_date' => 'date',
        'track_expiry' => 'boolean',
        'requires_prescription' => 'boolean',
        'controlled_substance' => 'boolean',
        'age_restricted' => 'boolean',
        'discount_allowed' => 'boolean',
        'active' => 'boolean',
        'storage_temperature' => 'decimal:2',
        'shelf_life' => 'integer',
        'package_size' => 'integer',
    ];

    protected $dates = [
        'expiry_date',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the supplier that owns the product.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the stocks for the product.
     */
    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    /**
     * Get the order items for the product.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }

    /**
     * Get the current stock attribute.
     */
    public function getCurrentStockAttribute(): int
    {
        return $this->stocks()->sum('quantity_stock');
    }

    /**
     * Get the stock value attribute.
     */
    public function getStockValueAttribute(): float
    {
        return $this->current_stock * $this->price;
    }

    /**
     * Get the cost value attribute.
     */
    public function getCostValueAttribute(): float
    {
        return $this->current_stock * ($this->cost_price ?? $this->price * 0.7);
    }

    /**
     * Get the profit margin attribute.
     */
    public function getProfitMarginAttribute(): float
    {
        if (!$this->cost_price) return 0;
        return (($this->price - $this->cost_price) / $this->cost_price) * 100;
    }

    /**
     * Check if product is low stock.
     */
    public function getIsLowStockAttribute(): bool
    {
        $minStock = $this->min_stock ?? config('pharmacy.stock.low_stock_threshold', 10);
        return $this->current_stock <= $minStock;
    }

    /**
     * Check if product is critically low stock.
     */
    public function getIsCriticalStockAttribute(): bool
    {
        $criticalThreshold = config('pharmacy.stock.critical_stock_threshold', 5);
        return $this->current_stock <= $criticalThreshold;
    }

    /**
     * Check if product is out of stock.
     */
    public function getIsOutOfStockAttribute(): bool
    {
        return $this->current_stock === 0;
    }

    /**
     * Check if product is expiring soon.
     */
    public function getIsExpiringSoonAttribute(): bool
    {
        if (!$this->track_expiry || !$this->expiry_date) {
            return false;
        }
        
        $warningDays = config('pharmacy.stock.expiry_warning_days', 30);
        return $this->expiry_date->lte(now()->addDays($warningDays));
    }

    /**
     * Check if product is critically expiring.
     */
    public function getIsCriticallyExpiringAttribute(): bool
    {
        if (!$this->track_expiry || !$this->expiry_date) {
            return false;
        }
        
        $criticalDays = config('pharmacy.stock.critical_expiry_days', 7);
        return $this->expiry_date->lte(now()->addDays($criticalDays));
    }

    /**
     * Check if product is expired.
     */
    public function getIsExpiredAttribute(): bool
    {
        if (!$this->track_expiry || !$this->expiry_date) {
            return false;
        }
        
        return $this->expiry_date->lt(now());
    }

    /**
     * Get the stock status attribute.
     */
    public function getStockStatusAttribute(): string
    {
        if ($this->is_out_of_stock) return 'out_of_stock';
        if ($this->is_critical_stock) return 'critical';
        if ($this->is_low_stock) return 'low_stock';
        return 'in_stock';
    }

    /**
     * Get the expiry status attribute.
     */
    public function getExpiryStatusAttribute(): string
    {
        if (!$this->track_expiry) return 'not_tracked';
        if ($this->is_expired) return 'expired';
        if ($this->is_critically_expiring) return 'critical';
        if ($this->is_expiring_soon) return 'expiring_soon';
        return 'good';
    }

    // Scopes for querying
    public function scopeLowStock($query)
    {
        return $query->withSum('stocks', 'quantity_stock')
                    ->whereRaw('(COALESCE(stocks_sum_quantity_stock, 0)) <= COALESCE(min_stock, 10)');
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('track_expiry', true)
                    ->where('expiry_date', '<=', now()->addDays($days))
                    ->where('expiry_date', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('track_expiry', true)
                    ->where('expiry_date', '<', now());
    }
}