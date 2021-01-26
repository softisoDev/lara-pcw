<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class FixProductUrls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:fix-urls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix product urls';

    /**
     * @var string
     */
    protected $cacheKey = 'product.url.fixer.last.id';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $lastCheckedId = \Cache::get($this->cacheKey);

        $products = ProductVariation::query()
            ->withoutGlobalScopes()
            ->whereHas('source', function ($query) {
                $query->withoutGlobalScopes()->where('name', 'walmart.com');
            })
            ->where('id', '>', !is_null($lastCheckedId) ? $lastCheckedId : 0)
            ->get();

        foreach ($products as $product) {
            $httpCode = getStatusCodeOfUrl($product->source_url);

            if ($httpCode == 404) {
                $product->source_url = modifyWalmartUrl($product->source_url);
                $product->save();

                //remove from cache
                Product::forgetOne(sprintf(Product::REMOVABLE_CACHE_NAME['single'], $product->product_id));
            }
            echo sprintf("%s \n\r", $product->source_url);
        }

        Cache::forever($this->cacheKey, ProductVariation::query()->select(['id'])->orderByDesc('id')->first()->id);


        $this->info('All links are fixed successfully');

    }
}
