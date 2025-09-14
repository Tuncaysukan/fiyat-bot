<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScanProductPrice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $productId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(\App\Services\PriceChecker $checker): void {
        $p = \App\Models\Product::with(['rule','marketplace'])->findOrFail($this->productId);
        if ($p->is_active) $checker->handle($p);
    }
}
