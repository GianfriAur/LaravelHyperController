<?php

namespace Gianfriaur\HyperController\Service\MacroProvider;

use Gianfriaur\HyperController\Http\Controllers\HyperController;
use Gianfriaur\HyperController\Service\AnnotationParserService\AnnotationParserServiceInterface;
use Gianfriaur\PackageLoader\PackageProvider\PackageWithLocalizationInterface;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Translation\Translator;

readonly class DefaultMacroProvider implements MacroProviderInterface
{

    private AnnotationParserServiceInterface $annotationParserService;

    /** @noinspection PhpPropertyOnlyWrittenInspection */
    public function __construct(private Application $app, private array $options)
    {
        $this->annotationParserService = $this->app->get(AnnotationParserServiceInterface::class);

    }


    // make sure in every condition the callback was called also after already resolved
    protected function callAfterResolving($name, $callback)
    {
        $this->app->afterResolving($name, $callback);

        if ($this->app->resolved($name)) {
            $callback($this->app->make($name), $this->app);
        }
    }


    public function generateRouteData($hyperControllerName): array
    {
        $controller_describer = $this->annotationParserService->getHyperControllerDescriber($hyperControllerName);

        $http_methods = array_map(fn($m) => $m->name, $controller_describer->methods);
        $has_index = $controller_describer->hasIndex;
        $actions = array_filter(array_map(fn($a) => $a->getNormalizedPartialPath(), $controller_describer->actions), fn($m) => $m !== '');
        $base_path = $controller_describer->basePath;
        $alias = $controller_describer->baseAlias;
        $alias_actions = array_map(fn($a) => ['fullPath'=>$a->fullPath, 'fullAlias'=>$a->fullAlias],  array_filter($controller_describer->actions,fn($m) => !$m->index ));
        $aliases=[];
        foreach ($alias_actions as $alias_action ) $aliases[$alias_action['fullAlias']] = $alias_action['fullPath'];
        return [
            'has_index' => $has_index,
            'base_path' => $base_path,
            'http_methods' => $http_methods,
            'alias' => $alias,
            'actionsRegex' => implode('|', $actions),
            'aliases' =>$aliases
        ];
    }


    function registerMacros(): void
    {

        $selfMacroProvider = $this;

        if (!Route::hasMacro('hyperController')) {
            Route::macro('hyperController', function (string $hyperControllerName) use ($selfMacroProvider) {

                [$has_index, $base_path, $http_methods, $alias, $actionsRegex] =
                    array_values(
                        Cache::store('hyper-controller')->rememberForever(
                            $hyperControllerName,
                            fn() => $selfMacroProvider->generateRouteData($hyperControllerName)
                        )
                    );

                /** @var Router $this */
                $route = $this->match($http_methods, $base_path . '/{action' . ($has_index ? '?' : '') . '}', [$hyperControllerName, 'handle']);

                $route->where('action', $actionsRegex);

                if (!is_null($alias)) {
                    $route->name($alias);
                }

                return $route;
            });
        }


    }


}