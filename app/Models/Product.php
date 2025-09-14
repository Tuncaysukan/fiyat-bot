<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $fillable = [
        'marketplace_id',
        'title',
        'url',
        'parse_strategy',
        'selector',
        'currency',
        'last_price',
        'is_active',
    ];

    protected $casts = [
        'last_price' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function marketplace(): BelongsTo
    {
        return $this->belongsTo(Marketplace::class);
    }

    public function rule(): HasOne
    {
        return $this->hasOne(PriceRule::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(PriceHistory::class);
    }

    public function getLatestPriceAttribute()
    {
        return $this->history()->latest()->first()?->price ?? $this->last_price;
    }

    public function getPriceChangeAttribute()
    {
        $latest = $this->history()->latest()->first();
        $previous = $this->history()->latest()->skip(1)->first();

        if (!$latest || !$previous) {
            return 0;
        }

        return $latest->price - $previous->price;
    }

    public function getPriceChangePercentAttribute()
    {
        $latest = $this->history()->latest()->first();
        $previous = $this->history()->latest()->skip(1)->first();

        if (!$latest || !$previous || $previous->price == 0) {
            return 0;
        }

        return (($latest->price - $previous->price) / $previous->price) * 100;
    }
}
