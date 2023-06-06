<?php
/** @noinspection PhpUndefinedClassInspection */
namespace Gianfriaur\HyperController\PackageLoader;

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
        for ($i = 0; $i < 10; $i++) {
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
}
// TEMPO MEDIO 10 HyperController con 10 rotte senza cache (100 rotte)
// Execution time of script = 0.0083539485931396 sec

// TEMPO MEDIO 10 HyperController con 10 rotte con cache (100 rotte)
// Execution time of script = 0.0020861625671387 sec

// TEMPO MEDIO registrazione 100 rotte normali di laravel
//Execution time of script = 0.0047791004180908 sec

// tempo medio handle e risoluzione rotta di HyperController
//Execution time of script = 0.00077104568481445 sec


// TEMPO MEDIO 100 HyperController con 10 rotte senza cache (1000 rotte)
// Execution time of script = 0.0103920955657959 sec

// TEMPO MEDIO 100 HyperController con 10 rotte con cache (1000 rotte)
// Execution time of script = 0.015992879867554 sec

// TEMPO MEDIO registrazione 1000 rotte normali di laravel
//Execution time of script = 0.054811000823975 sec

// tempo medio handle e risoluzione rotta di HyperController
//Execution time of script = 0.0008389949798584 sec