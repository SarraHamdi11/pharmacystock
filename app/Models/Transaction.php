<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionFactory> */
    use HasFactory;


/**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'transactionel_id',
        'transactionel_type',
        'operation',
    ];


      /**
     * Get the parent transactionel model (customer or supplier).
     */
    public function transactionel(): MorphTo
    {
        return $this->morphTo();
    }

}
