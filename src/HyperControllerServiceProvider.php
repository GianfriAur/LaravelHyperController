<?php

namespace Gianfriaur\HyperController;

use Gianfriaur\HyperController\ServiceProvider\RoutingServiceProvider;
use Gianfriaur\HyperController\ServiceProvider\ServicesProvider;
use Illuminate\Support\ServiceProvider;

class HyperControllerServiceProvider extends ServiceProvider
{
    const CONFIG_NAMESPACE = "hyper_controller";
    const CONFIG_FILE_NANE = "hyper_controller.php";

    public function boot(): void
    {
        $this->bootConfig();
    }


    public function register(): void
    {
        $this->registerConfig();

        $this->app->register(ServicesProvider::class);
        $this->app->register(RoutingServiceProvider::class);
    }

    private function bootConfig(): void
    {
        $this->publishes([
            __DIR__ . '/../config/' . self::CONFIG_FILE_NANE => config_path(self::CONFIG_FILE_NANE),
        ]);
    }

    private function registerConfig(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/' . self::CONFIG_FILE_NANE, self::CONFIG_NAMESPACE
        );
    }

}