<?php

namespace Gianfriaur\HyperController\Http\Controllers;

use App\Http\Controllers\Controller;
use Gianfriaur\HyperController\Describer\HyperControllerActionDescriber;
use Gianfriaur\HyperController\Service\ResolverService\HyperControllerDependencyResolverInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;

class HyperController extends Controller
{
    /**
     * @throws BindingResolutionException
     */
    protected function resolveDependencies(HyperControllerActionDescriber $resolvedMethod, array $httpQueryArguments = []): array
    {
        return app()->make(HyperControllerDependencyResolverInterface::class)->resolveHyperController($this, $resolvedMethod,$httpQueryArguments);
    }

    /**
     * @throws BindingResolutionException
     */
    protected function resolveMethod(?string $method): HyperControllerActionDescriber
    {
        return app()->make(HyperControllerDependencyResolverInterface::class)->extractHyperControllerActionDescriber($this, $method);
    }




    /**
     * @throws BindingResolutionException
     */
    private function callMethod(?string $method, array $parameters = [])
    {

        $resolvedMethod = $this->resolveMethod($method);

        return call_user_func_array([$this, $resolvedMethod->getMethodName()],
            $this->resolveDependencies( $resolvedMethod,$parameters)
        );
    }


    /**
     * @throws BindingResolutionException
     */
    public function handle(Request $request, ...$parameters)
    {
        $method = Route::current()->parameter('action', Arr::last($parameters));

        $prepare = collect($parameters)
            ->merge($request->query())
            ->diffAssoc([$method])
            ->all();

        return $this->callMethod($method, $prepare) ?? back();
    }
}
