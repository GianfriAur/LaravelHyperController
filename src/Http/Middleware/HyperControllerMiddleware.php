<?php

namespace Gianfriaur\HyperController\Http\Middleware;

use Closure;
use Gianfriaur\HyperController\Service\AnnotationParserService\AnnotationParserServiceInterface;
use Gianfriaur\HyperController\Service\ResolverService\HyperControllerDependencyResolverInterface;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Routing\MiddlewareNameResolver;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class HyperControllerMiddleware
{

    public function handle(Request $request, Closure $next, string $hyperControllerClass): Response
    {
        // get annotation service
        $annotationParserService = app()->get(AnnotationParserServiceInterface::class);

        //get hyper_controller describer
        $controller_describer = $annotationParserService->getHyperControllerDescriber($hyperControllerClass);

        // get hyper_controller action describer
        $action_describer = app()->make(HyperControllerDependencyResolverInterface::class)->extractHyperControllerActionDescriber($controller_describer, Route::current()->parameter('action'));

        //get all middlewares and middlewares_group
        $middlewares = array_merge(array_diff($controller_describer->middlewares, $action_describer->withoutMiddlewares), $action_describer->middlewares);

        // resolve all middlewares er 'web' => [ web_middleware_class_1, web_middleware_class_2, ... ]
        $middlewares_stack = array_merge(
            ...array_map(
                fn($middleware) => (array)MiddlewareNameResolver::resolve(
                    $middleware,
                    app('router')->getMiddleware(),
                    app('router')->getMiddlewareGroups()
                ),
                $middlewares
            )
        );

        // continue with all other middlewares in middlewares_stack
        return app(Pipeline::class)
            ->send($request)
            ->through($middlewares_stack)
            ->then($next);

    }
}