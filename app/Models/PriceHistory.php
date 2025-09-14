<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceHistory extends Model
{
    protected $table = 'price_history';

    protected $fillable = [
        'product_id',
        'price',
        'raw',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'raw' => 'array',
        'created_at' => 'datetime',
    ];

    public $timestamps = false;

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
