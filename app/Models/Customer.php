<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Order;
class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'address',
        'phone',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class);
    }
}