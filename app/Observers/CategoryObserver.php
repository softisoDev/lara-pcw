<?php

namespace App\Observers;

use App\Models\Category;

class CategoryObserver
{
    /**
     * Handle the category "created" event.
     *
     * @param Category $category
     * @return void
     */
    public function created(Category $category)
    {
        $category->updateSlug();
    }

    /**
     * Handle the category "updated" event.
     *
     * @param Category $category
     * @return void
     */
    public function updated(Category $category)
    {
        Category::forgetOne(sprintf(Category::REMOVABLE_CACHE_NAME['page'], $category->id));
        Category::forgetOne(sprintf(Category::REMOVABLE_CACHE_NAME['self'], $category->id));
        refreshHomePageCache();

        if ($category->isDirty(['_lft', 'parent_id', '_rgt'])) {
            Category::forgetOne(sprintf(Category::REMOVABLE_CACHE_NAME['descendant'], $category->id));
            Category::forgetOne(sprintf(Category::REMOVABLE_CACHE_NAME['ancestor'], $category->id));
        }
    }

    /**
     * Handle the category "deleted" event.
     *
     * @param Category $category
     * @return void
     */
    public function deleted(Category $category)
    {
        Category::forgetOne(sprintf(Category::REMOVABLE_CACHE_NAME['page'], $category->id));
        Category::forgetOne(sprintf(Category::REMOVABLE_CACHE_NAME['self'], $category->id));
        refreshHomePageCache();

        if ($category->isDirty(['_lft', 'parent_id', '_rgt'])) {
            Category::forgetOne(sprintf(Category::REMOVABLE_CACHE_NAME['descendant'], $category->id));
            Category::forgetOne(sprintf(Category::REMOVABLE_CACHE_NAME['ancestor'], $category->id));
        }
    }

    /**
     * Handle the category "restored" event.
     *
     * @param Category $category
     * @return void
     */
    public function restored(Category $category)
    {
        Category::forgetOne(sprintf(Category::REMOVABLE_CACHE_NAME['page'], $category->id));
        Category::forgetOne(sprintf(Category::REMOVABLE_CACHE_NAME['self'], $category->id));
        refreshHomePageCache();

        if ($category->isDirty(['_lft', 'parent_id', '_rgt'])) {
            Category::forgetOne(sprintf(Category::REMOVABLE_CACHE_NAME['descendant'], $category->id));
            Category::forgetOne(sprintf(Category::REMOVABLE_CACHE_NAME['ancestor'], $category->id));
        }
    }

    /**
     * Handle the category "force deleted" event.
     *
     * @param Category $category
     * @return void
     */
    public function forceDeleted(Category $category)
    {
        Category::forgetOne(sprintf(Category::REMOVABLE_CACHE_NAME['page'], $category->id));
        Category::forgetOne(sprintf(Category::REMOVABLE_CACHE_NAME['self'], $category->id));
        refreshHomePageCache();

        if ($category->isDirty(['_lft', 'parent_id', '_rgt'])) {
            Category::forgetOne(sprintf(Category::REMOVABLE_CACHE_NAME['descendant'], $category->id));
            Category::forgetOne(sprintf(Category::REMOVABLE_CACHE_NAME['ancestor'], $category->id));
        }
    }
}
