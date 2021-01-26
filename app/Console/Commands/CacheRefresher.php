<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Console\Command;

class CacheRefresher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larapcw:refresh-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Renew all cache';

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
        refreshHomePageCache();
        Product::forgetOne(Product::REMOVABLE_CACHE_NAME['all_products']);
        Category::forgetOne(Category::REMOVABLE_CACHE_NAME['all_pages']);
        Tag::forgetOne(Tag::REMOVABLE_CACHE_NAME['all_pages']);
    }
}
