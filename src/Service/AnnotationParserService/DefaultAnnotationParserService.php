<?php

namespace Gianfriaur\HyperController\Service\AnnotationParserService;

use Gianfriaur\HyperController\Attribute\Http\Controller\Action;
use Gianfriaur\HyperController\Attribute\Http\Controller\Controller;
use Gianfriaur\HyperController\Attribute\Http\Controller\IndexAction;
use Gianfriaur\HyperController\Attribute\Http\Controller\Middleware;
use Gianfriaur\HyperController\Attribute\Http\Controller\WithoutMiddleware;
use Gianfriaur\HyperController\Describer\HyperControllerActionDescriber;
use Gianfriaur\HyperController\Describer\HyperControllerDescriber;
use Gianfriaur\HyperController\Exception\HyperControllerAliasAnnotationException;
use Gianfriaur\HyperController\Exception\HyperControllerMissingControllerAnnotationException;
use Illuminate\Foundation\Application;
use ReflectionClass;
use ReflectionMethod;

readonly class DefaultAnnotationParserService implements AnnotationParserServiceInterface
{
    /** @noinspection PhpPropertyOnlyWrittenInspection */
    public function __construct(private Application $app, private array $options)
    {
    }

    /**
     * @throws HyperControllerMissingControllerAnnotationException
     */
    public function getControllerMetadata(ReflectionClass $reflection): array
    {
        foreach ($reflection->getAttributes() as $attribute) {
            if ($instance = $attribute->newInstance()) {
                if ($instance instanceof Controller) {
                    return [
                        $instance->path,
                        $instance->alias
                    ];
                }
            }
        }
        throw new HyperControllerMissingControllerAnnotationException($reflection->getName());
    }

    public function getMiddlewares(ReflectionMethod|ReflectionClass $reflection): array
    {
        foreach ($reflection->getAttributes() as $attribute) {
            if ($instance = $attribute->newInstance()) {
                if ($instance instanceof Middleware) {
                    return $instance->middlewares;
                }
            }
        }
        return [];
    }

    public function getWithoutMiddlewares(ReflectionMethod $reflection): array
    {
        foreach ($reflection->getAttributes() as $attribute) {
            if ($instance = $attribute->newInstance()) {
                if ($instance instanceof WithoutMiddleware) {
                    return $instance->middlewares;
                }
            }
        }
        return [];
    }

    /**
     * @throws HyperControllerAliasAnnotationException
     * @return array<HyperControllerActionDescriber>
     */
    public function getControllerActionMetadata(ReflectionClass $reflection, string $path, ?string $alias): array
    {
        $routes = [];

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            foreach ($method->getAttributes() as $attribute) {
                if ($instance = $attribute->newInstance()) {
                    if ($instance instanceof IndexAction) {
                        $routes[] = new HyperControllerActionDescriber(
                            partialPath: '',
                            fullPath: $path,
                            partialAlias: '',
                            fullAlias: $alias,
                            reflectedPointer: $reflection->getName().'@'. $method->getName(),
                            index: true,
                            methods: is_array($instance->method) ? $instance->method : [$instance->method],
                            middlewares: $this->getMiddlewares($method),
                            withoutMiddlewares: $this->getWithoutMiddlewares($method)
                        );
                    } elseif ($instance instanceof Action) {

                        if ($instance->alias && !$alias) {
                            throw new HyperControllerAliasAnnotationException($reflection->getName());
                        }
                        $routes[] = new HyperControllerActionDescriber(
                            partialPath: ($instance->path ?? $method->name),
                            fullPath:$path . '/' . ($instance->path ?? $method->name),
                            partialAlias: ($instance->alias ?? ($instance->name ?? $method->name)),
                            fullAlias:  $alias . '.' . ($instance->alias ?? ($instance->name ?? $method->name)),
                            reflectedPointer: $reflection->getName().'@'. $method->getName(),
                            index: false,
                            methods: is_array($instance->method) ? $instance->method : [$instance->method],
                            middlewares: $this->getMiddlewares($method),
                            withoutMiddlewares: $this->getWithoutMiddlewares($method)
                        );
                    }
                }
            }
        }

        return $routes;
    }

    private function uniqueMethods($data): array
    {
        return array_values(array_intersect_key($data, array_unique(array_map(fn($m) => $m->value, $data))));
    }

    /**
     * @throws HyperControllerMissingControllerAnnotationException|HyperControllerAliasAnnotationException
     */
    function getHyperControllerDescriber($className): HyperControllerDescriber
    {

        $reflection = new ReflectionClass($className);

        //dd($reflection);

        [$path, $alias] = $this->getControllerMetadata($reflection);
        $methods = $this->getControllerActionMetadata($reflection, $path, $alias);
        $has_index = count(array_filter($methods, fn($e) => $e->index)) > 0;
        $all_methods = array_merge(...array_map(fn($e) => $e->methods, $methods));

        $middlewares = $this->getMiddlewares($reflection);
        $middlewares_r = array_unique(array_merge(...array_map(fn($actions)=>$actions->withoutMiddlewares,$methods)));

        $describer = new HyperControllerDescriber(
            basePath: $path,
            baseAlias: $alias,
            hasIndex: $has_index,
            actions: $methods,
            methods: $this->uniqueMethods($all_methods),
            class: $className,
            middlewares: $middlewares,
            skip_middlewares: $middlewares_r
        );
        return $describer;
    }
}