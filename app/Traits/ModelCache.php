<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Predis\Client;

/**
 * @method Collection|self remember()
 * @method static forgetByEvent(array $eventKeys = []) forget cache by event
 */
trait ModelCache
{
    protected static $cacheDataStore = [];

    public static function bootModelSelfCache()
    {
        static::updated($callback = function (self $model) {
            $model->forgetSelfCache();
        });
        static::deleted($callback);
//        static::observe(ModelCache::class);
    }

    public function forgetSelfCache()
    {
        Cache::forget(static::cacheName(sprintf(self::CACHE_NAME['self'], $this->id)));
    }

    public function resolveRouteBinding($value)
    {
        return static::cacheStore(sprintf(self::CACHE_NAME['self'], $value), Carbon::now()->addMonths(), function () use ($value) {
            return parent::resolveRouteBinding($value);
        });
    }

    public static function cacheName($name, $paths = [])
    {
        return preg_replace('/\\\\/', "_", static::class) . ':' . implode(':', $paths) . $name;
    }

    public function scopeRemember(Builder $query, ...$parameters)
    {
        return static::cacheStore(static::cacheName(md5($query->toSql()), ['remember']), Carbon::tomorrow(), function () use ($query, $parameters) {
            return $query->get(...$parameters);
        });
    }

    public static function cacheStore($name, $ttl, $data)
    {
        $name = self::cacheName($name);

        if (!array_key_exists($name, static::$cacheDataStore)) {
            static::$cacheDataStore[$name] = Cache::remember($name, $ttl, $data);
        }

        return static::$cacheDataStore[$name];
    }

    public function forgetObserve($event)
    {
        $keys = Arr::flatten(array_map(function ($key) {
            return $this->getKeys($key);
        }, self::OBSERVE_CACHE[$event]));

        $this->forgetKeys($keys);
    }

    public function scopeForgetByEvent($query, array $eventKeys = [])
    {
        $keys = Arr::flatten(array_map(function ($key) {
            return $this->getKeys($key);
        }, $eventKeys));

        $this->forgetKeys($keys);
    }

    public function getKeys($prefix = "")
    {
        return $this->getRedis()->keys(env('CACHE_PREFIX') . ':' . self::cacheName($prefix) . '*');
    }

    protected function forgetKeys($keys)
    {
        if (!empty($keys)) {
            $this->getRedis()->del(array_map(function ($key) {
                return Str::after($key, strtolower(env('APP_NAME')) . '_database_');
            }, $keys));
        }
    }

    public static function forgetOne($prefix)
    {
        $keys = (new self())->getKeys($prefix);
        (new self())->forgetKeys($keys);
    }

    /**
     * @return \Illuminate\Redis\Connections\Connection|Client
     */
    protected function getRedis()
    {
        return Redis::connection('cache');
    }

}
