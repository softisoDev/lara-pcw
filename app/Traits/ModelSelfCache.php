<?php

namespace App\Traits;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

trait ModelSelfCache
{
    public static function bootModelSelfCache()
    {
        static::updated($callback = function (self $model) {
            $model->forgetSelfCache();
        });
        static::deleted($callback);
    }

    public function forgetSelfCache()
    {
        Cache::forget(static::class . ':self:' . $this->id);
    }

    public function resolveRouteBinding($value)
    {
        return Cache::remember(static::class . ':self:' . $value, Carbon::now()->addDay(), function () use ($value) {
            return parent::resolveRouteBinding($value);
        });
    }

}
