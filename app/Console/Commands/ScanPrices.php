<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ScanPrices extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'price:scan {--only=id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aktif ürünleri sırayla tarar';

    /**
     * Execute the console command.
     */
    public function handle(){
        $query = \App\Models\Product::with(['rule','marketplace'])->where('is_active',true);
        if ($id = $this->option('only')) $query->where('id',$id);
        $query->chunk(20, function($batch){
            foreach ($batch as $p) {
                \App\Jobs\ScanProductPrice::dispatch($p->id)->onQueue('pricing');
                usleep(300000); // 0.3s siteyi yormama gecikmesi
            }
        });
        $this->info('Queued.');
    }
}
