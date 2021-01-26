<?php

namespace App\Console\Commands;

use App\Jobs\Cache\RefreshAllCategoryPages;
use App\Libraries\BesCache\ProductCache;
use App\Models\Product;
use Illuminate\Console\Command;
use Spatie\MediaLibrary\Models\Media;

class ProductMainImageSetter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:fix-broken-main-media';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set main image of product which has not been set';

    private $cacheKey = "product.main.media.lastCheckedId";

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

        $products = Product::whereNotIn('id', Media::query()
            ->select('model_id')
            ->where('model_type', Product::class)
            ->whereLike('custom_properties', '%is_main%')
        )
            ->where('id', '>', !is_null($lastCheckedId) ? $lastCheckedId : 0)
            ->get();

        foreach ($products as $product) {
            try {
                $product->getMedia('image')->first()->setCustomProperty('is_main', true)->save();
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
            }
        }

        \Cache::forever($this->cacheKey, Product::select('id')->orderByDesc('id')->first()->id);

        $this->info("Products media are updated successfully");
    }
}
