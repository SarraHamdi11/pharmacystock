<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Medicament extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'dosage',
        'forme',
        'fabricant',
        'date_expiration',
        'seuil_alerte',
    ];

    protected $dates = [
        'date_expiration',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}