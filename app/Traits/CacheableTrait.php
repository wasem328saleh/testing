<?php
namespace App\Traits;
use Illuminate\Support\Facades\Cache;

trait CacheableTrait
{
    protected static function bootCacheable()
    {
        static::created(function ($model) {
            $model->flushCache();
        });

        static::updated(function ($model) {
            $model->flushCache();
        });

        static::deleted(function ($model) {
            $model->flushCache();
        });
    }

    public function flushCache()
    {
        $cacheKey = $this->getCacheKey();
        Cache::forget($cacheKey);
    }

    public function getCacheKey()
    {
        return get_class($this) . '_' . $this->getKey();
    }

    public function getCachedData()
    {
        $cacheKey = $this->getCacheKey();
        return Cache::remember($cacheKey, now()->addMinutes(10), function () {
            return $this->toArray();
        });
    }
}
