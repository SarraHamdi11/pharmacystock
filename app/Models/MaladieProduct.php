<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class MaladieProduct extends Model
{
    use HasFactory;

    protected $table = 'maladie_products';
    protected $fillable = [
        'maladie_id',
        'product_id',
    ];

    public $timestamps = true;  

    public function maladie(): BelongsTo
    {
        return $this->belongsTo(Maladie::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}