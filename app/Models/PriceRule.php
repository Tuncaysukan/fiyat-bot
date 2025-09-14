<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceRule extends Model
{
    protected $fillable = [
        'product_id',
        'drop_percent',
        'drop_amount',
    ];

    protected $casts = [
        'drop_percent' => 'decimal:2',
        'drop_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
