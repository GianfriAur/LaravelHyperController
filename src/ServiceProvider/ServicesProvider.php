<?php

namespace Gianfriaur\HyperController\ServiceProvider;

use Gianfriaur\HyperController\Exception\BadPackageLoaderAutoloadException;
use Gianfriaur\HyperController\Exception\BadServiceInterfaceException;
use Gianfriaur\HyperController\Exception\HyperControllerMissingConfigException;
use Gianfriaur\HyperController\PackageLoader\PackageLoaderInjector;
use Gianfriaur\HyperController\Service\AnnotationParserService\AnnotationParserServiceInterface;
use Gianfriaur\HyperController\Service\CacheService\CacheServiceInterface;
use Gianfriaur\HyperController\Service\MacroProvider\MacroProviderInterface;
use Gianfriaur\HyperController\Service\ResolverService\HyperControllerDependencyResolverInterface;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ServicesProvider extends ServiceProvider implements DeferrableProvider
{
    const CONFIG_NAMESPACE = "hyper_controller";
    const CONFIG_FILE_NANE = "hyper_controller.php";

    protected array $commands = [];

    public function boot(): void
    {
    }

    /**
     * @throws BadServiceInterfaceException
     * @throws HyperControllerMissingConfigException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function register(): void
    {
        $this->registerSingletonService('resolver', 'resolvers', HyperControllerDependencyResolverInterface::class, 'hyper_controller.resolver', false);
        $this->registerSingletonService('macro_provider', 'macro_providers', MacroProviderInterface::class, 'hyper_controller.macro_provider', false);
        $this->registerSingletonService('annotation_parser', 'annotation_parsers', AnnotationParserServiceInterface::class, 'hyper_controller.annotation_parser', false);

        $this->registerSingletonService('cache_service', 'cache_services', CacheServiceInterface::class, 'hyper_controller.cache_service', false);

        $this->app->get(MacroProviderInterface::class)->registerMacros();


        $package_loader_autoload = $this->getConfig('package_loader_autoload', true);

        if ($package_loader_autoload === 'auto' || $package_loader_autoload === true) {

            $has_cache_service = !$this->getConfig('cache_service', true) !== true;

            if (!$has_cache_service) {
                $this->callAfterResolving('\Gianfriaur\PackageLoader\Service\PackageProviderService\PackageProviderServiceInterface', function ($packageProvide) {
                    (
                    new PackageLoaderInjector(
                        $this->app,
                        $packageProvide
                    ))
                        ->registerHyperController();
                });
            } else {
                $this->callAfterResolving('\Gianfriaur\PackageLoader\Service\PackageProviderService\PackageProviderServiceInterface', function ($packageProvide) {

                    $this->callAfterResolving('cache', function ($cache) use ($packageProvide) {

                        $this->app->get(CacheServiceInterface::class)->registerCacheStore();

                        (new PackageLoaderInjector($this->app, $packageProvide))->registerHyperController();
                    });
                });
            }

        }

        if ($this->app->runningInConsole()) {
            $this->registerCommands();
        }
    }


    /**
     * @throws HyperControllerMissingConfigException
     */
    private function getGenericServiceDefinition(string $strategy_name, string $strategy_collection_name, bool $nullable = false): array
    {
        $strategy = $this->getConfig($strategy_name, $nullable);
        if ($strategy === null) return [null, []];
        $strategy_collection = $this->getConfig($strategy_collection_name);
        return [$strategy_collection[$strategy]['class'], $strategy_collection[$strategy]['options']];
    }

    /**
     * @throws HyperControllerMissingConfigException
     * @throws BadServiceInterfaceException
     * @noinspection PhpReturnValueOfMethodIsNeverUsedInspection
     * @noinspection PhpSameParameterValueInspection
     */
    private function registerSingletonService(string $strategy_name, string $strategy_collection_name, string $strategy_interface, string $strategy_alias, bool $obligatory): bool
    {
        [$class, $options] = $this->getGenericServiceDefinition($strategy_name, $strategy_collection_name, $obligatory);
        if ($class !== null) {
            if (!is_subclass_of($class, $strategy_interface)) {
                throw new BadServiceInterfaceException($strategy_name, $class, $strategy_interface);
            }
            // register singleton
            $this->app->singleton($strategy_interface, function ($app) use ($class, $options) {
                return new $class($app, $options);
            });
            $this->app->alias($strategy_interface, $strategy_alias);
            return true;
        }
        return false;
    }

    /**
     * @throws HyperControllerMissingConfigException
     */
    private function getConfig($name, bool $nullable = false): mixed
    {
        if (!$config = config(self::CONFIG_NAMESPACE . '.' . $name)) {
            if (!$nullable) throw new HyperControllerMissingConfigException($name);
        }
        return $config;
    }

    private function registerCommands(): void
    {
        if (sizeof($this->commands) > 0) {
            $this->commands(array_values($this->commands));
        }
    }

    /**
     * @throws HyperControllerMissingConfigException
     * @throws BadPackageLoaderAutoloadException
     * @noinspection RedundantSuppression
     */
    public function provides(): array
    {
        $package_loader_autoload = $this->getConfig('package_loader_autoload', true);

        if ($package_loader_autoload === 'auto' || $package_loader_autoload === true) {
            if (interface_exists('\Gianfriaur\PackageLoader\Service\PackageProviderService\PackageProviderServiceInterface')) {
                /** @noinspection PhpUndefinedClassInspection */
                return ['\Gianfriaur\PackageLoader\Service\PackageProviderService\PackageProviderServiceInterface'];
            } else {
                if ($package_loader_autoload === true) {
                    throw new BadPackageLoaderAutoloadException();
                }
            }
        }
        return [];
    }

}