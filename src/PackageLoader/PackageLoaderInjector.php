<?php
/** @noinspection PhpUndefinedClassInspection */
namespace Gianfriaur\HyperController\PackageLoader;

use Barryvdh\Debugbar\Facades\Debugbar;
use Gianfriaur\PackageLoader\Service\PackageProviderService\PackageProviderServiceInterface;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;


readonly class PackageLoaderInjector
{
    /** @noinspection PhpPropertyOnlyWrittenInspection */
    public function __construct(private  Application $app, private PackageProviderServiceInterface $packageProviderService)
    { }

    public function registerHyperController(): void
    {
        foreach ($this->packageProviderService->getPackageProviders() as $package_name => $packageProvider) {
            if ($packageProvider instanceof PackageWithHyperController) {
                foreach ($packageProvider->getHyperControllers() as $controller) {
                    /** @noinspection PhpUndefinedMethodInspection */
                    Route::hyperController($controller);
                }
            }
        }
    }
}