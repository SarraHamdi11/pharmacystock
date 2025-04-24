<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderProduct extends Model
{
    use HasFactory;

    protected $table = 'product_orders'; 

    protected $fillable = [
        'product_id',
        'order_id',
        'price',
        'quantity',
    ];

    /**
     * Get the product associated with the order product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the order associated with the order product.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}