<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'name',
        'maladie_id' 
    ];

    
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    
    public function maladie(): BelongsTo
    {
        return $this->belongsTo(Maladie::class);
    }
}