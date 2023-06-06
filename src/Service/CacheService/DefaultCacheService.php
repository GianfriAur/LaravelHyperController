<?php

namespace Gianfriaur\HyperController\Service\CacheService;

use Gianfriaur\HyperController\Cache\HyperControllerStore;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

readonly class DefaultCacheService implements CacheServiceInterface
{
    public function __construct(protected Application $app, protected array $options)
    {
    }

    public function getCacheFilePath(): string
    {
        $options = $this->options;

        /* bypass private protection
         * ref https://stackoverflow.com/questions/70004276/php-closure-security-problem-can-modify-private-property-outside-of-class-is
         */

        /** @noinspection PhpUnnecessaryLocalVariableInspection */
        $absolute_path_cache_file = (function () use ($options) {
            /** @var Application $this */
            return $this->normalizeCachePath('HYPER_CONTROLLER_CACHE', $options['cache_file']);
        })->call($this->app);

        return $absolute_path_cache_file;
    }

    public function registerCacheStore()
    {
        $selfServiceProvider = $this;

        Config::set('cache.stores.hyper-controller', ['driver' => 'hyper-controller']);

        Cache::extend('hyper-controller', function (Application $app) use ($selfServiceProvider) {
            return Cache::repository(new HyperControllerStore(new Filesystem(), $selfServiceProvider->getCacheFilePath(), false));
        });

    }
}