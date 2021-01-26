<?php

namespace App\Observers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Model;

class ProductObserver
{

    public function created(Product $product)
    {
        refreshHomePageCache();
        Category::forgetOne(Category::REMOVABLE_CACHE_NAME['all_pages']);
        Tag::forgetOne(Tag::REMOVABLE_CACHE_NAME['all_pages']);
    }

    /**
     * Handle the product "updated" event.
     *
     * @param Product $product
     * @return void
     */
    public function updated(Product $product)
    {
        Product::forgetOne(sprintf(Product::REMOVABLE_CACHE_NAME['single'], $product->id));
        Product::forgetOne(sprintf(Product::REMOVABLE_CACHE_NAME['self'], $product->id));
        refreshHomePageCache();
    }

    public function deleted(Product $product)
    {
        Product::forgetOne(sprintf(Product::REMOVABLE_CACHE_NAME['single'], $product->id));
        Product::forgetOne(sprintf(Product::REMOVABLE_CACHE_NAME['self'], $product->id));

        Category::forgetOne(Category::REMOVABLE_CACHE_NAME['all_pages']);
        Tag::forgetOne(Tag::REMOVABLE_CACHE_NAME['all_pages']);

        refreshHomePageCache();
    }


    public function restored(Product $product)
    {
        Product::forgetOne(sprintf(Product::REMOVABLE_CACHE_NAME['single'], $product->id));
        Product::forgetOne(sprintf(Product::REMOVABLE_CACHE_NAME['self'], $product->id));

        Category::forgetOne(Category::REMOVABLE_CACHE_NAME['all_pages']);
        Tag::forgetOne(Tag::REMOVABLE_CACHE_NAME['all_pages']);

        refreshHomePageCache();
    }


    public function forceDeleted(Product $product)
    {
        Product::forgetOne(sprintf(Product::REMOVABLE_CACHE_NAME['single'], $product->id));
        Product::forgetOne(sprintf(Product::REMOVABLE_CACHE_NAME['self'], $product->id));

        Category::forgetOne(Category::REMOVABLE_CACHE_NAME['all_pages']);
        Tag::forgetOne(Tag::REMOVABLE_CACHE_NAME['all_pages']);

        refreshHomePageCache();
    }
}
