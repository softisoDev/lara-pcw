<?php

namespace App\Traits;

use Carbon\Carbon;

trait TotalCounter
{
    public static function bootTotalCounter()
    {
        static::updated($callback = function (self $model) {
            $model->forgetTotalCache();
            $model->total();
        });

        static::created($callback);

        static::deleted($callback);
    }

    public static function total()
    {
        return self::cacheStore(self::CACHE_NAME['total'], Carbon::now()->addMonths(), function () {
            return static::all()->count();
        });
    }

    public static function forgetTotalCache()
    {
        self::forgetOne(self::CACHE_NAME['total']);
    }
}
