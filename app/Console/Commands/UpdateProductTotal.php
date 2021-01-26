<?php

namespace App\Console\Commands;

use App\Libraries\BesCache\CategoryCache;
use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;


/**
 * Class UpdateProductTotal
 * @package App\Console\Commands
 */
class UpdateProductTotal extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'category:count-product';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Country products and update product total in category table';


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
        $categories = Category::whereIsRoot()->get();

        foreach ($categories as $category) {
            $category->update([
                'product_total' => $category->getTreeProducts()->count(),
                'updated_at'    => Carbon::now(),
            ]);
        }

        Category::forgetOne(Category::REMOVABLE_CACHE_NAME['navbar']);

        $this->info('Products are counted successfully');
    }


}
