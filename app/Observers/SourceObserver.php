<?php

namespace App\Observers;

use App\Models\Category;
use App\Models\Source;
use App\Models\Tag;

class SourceObserver
{

    /**
     * Handle the source "created" event.
     *
     * @param Source $source
     * @return void
     */
    public function created(Source $source)
    {
        refreshHomePageCache();
        Category::forgetOne(Category::REMOVABLE_CACHE_NAME['all_pages']);
        Tag::forgetOne(Tag::REMOVABLE_CACHE_NAME['all_pages']);
    }

    /**
     * Handle the source "updated" event.
     *
     * @param Source $source
     * @return void
     */
    public function updated(Source $source)
    {
        refreshHomePageCache();
        Category::forgetOne(Category::REMOVABLE_CACHE_NAME['all_pages']);
        Tag::forgetOne(Tag::REMOVABLE_CACHE_NAME['all_pages']);
    }

    /**
     * Handle the source "deleted" event.
     *
     * @param Source $source
     * @return void
     */
    public function deleted(Source $source)
    {
        refreshHomePageCache();
        Category::forgetOne(Category::REMOVABLE_CACHE_NAME['all_pages']);
        Tag::forgetOne(Tag::REMOVABLE_CACHE_NAME['all_pages']);
    }

    /**
     * Handle the source "restored" event.
     *
     * @param Source $source
     * @return void
     */
    public function restored(Source $source)
    {
        refreshHomePageCache();
        Category::forgetOne(Category::REMOVABLE_CACHE_NAME['all_pages']);
        Tag::forgetOne(Tag::REMOVABLE_CACHE_NAME['all_pages']);
    }

    /**
     * Handle the source "force deleted" event.
     *
     * @param Source $source
     * @return void
     */
    public function forceDeleted(Source $source)
    {
        refreshHomePageCache();
        Category::forgetOne(Category::REMOVABLE_CACHE_NAME['all_pages']);
        Tag::forgetOne(Tag::REMOVABLE_CACHE_NAME['all_pages']);
    }
}
