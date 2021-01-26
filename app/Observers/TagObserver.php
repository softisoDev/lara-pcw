<?php

namespace App\Observers;

use App\Models\Tag;

class TagObserver
{
    /**
     * Handle the tag "created" event.
     *
     * @param Tag $tag
     * @return void
     */
    public function created(Tag $tag)
    {
        Tag::forgetOne(Tag::REMOVABLE_CACHE_NAME['keyword']);
    }

    /**
     * Handle the tag "updated" event.
     *
     * @param Tag $tag
     * @return void
     */
    public function updated(Tag $tag)
    {
        Tag::forgetOne(Tag::REMOVABLE_CACHE_NAME['keyword']);
    }

    /**
     * Handle the tag "deleted" event.
     *
     * @param Tag $tag
     * @return void
     */
    public function deleted(Tag $tag)
    {
        Tag::forgetOne(Tag::REMOVABLE_CACHE_NAME['keyword']);
    }

    /**
     * Handle the tag "restored" event.
     *
     * @param Tag $tag
     * @return void
     */
    public function restored(Tag $tag)
    {
        Tag::forgetOne(Tag::REMOVABLE_CACHE_NAME['keyword']);
    }

    /**
     * Handle the tag "force deleted" event.
     *
     * @param Tag $tag
     * @return void
     */
    public function forceDeleted(Tag $tag)
    {
        Tag::forgetOne(Tag::REMOVABLE_CACHE_NAME['keyword']);
    }
}
