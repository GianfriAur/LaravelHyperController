<?php

namespace Gianfriaur\HyperController\Routing;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Routing\RouteCollectionInterface;
use Illuminate\Routing\UrlGenerator as BaseUrlGenerator;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class UrlGenerator extends BaseUrlGenerator
{
    
    protected ?array $hyperControllerRoutes = null;

    public function __construct(RouteCollectionInterface $routes, Request $request, $assetRoot = null)
    {
        parent::__construct($routes, $request, $assetRoot);
    }
    
    protected function generateHyperControllerRoutes(){
        $routes=[];

        $hyperControllers = Cache::store('hyper-controller')->getStore()->getKeys();

        foreach ($hyperControllers as $hyperController){
            $data = Cache::store('hyper-controller')->get($hyperController);
            $routes=array_merge($routes, $data['aliases']??[]);
        }

        $this->hyperControllerRoutes = $routes;
    }

    public function route($name, $parameters = [], $absolute = true)
    {

        if ($this->hyperControllerRoutes===null){
            $this->generateHyperControllerRoutes();
        }

        if (array_key_exists($name,$this->hyperControllerRoutes)){
            return $this->toRoute(new Route([],$this->hyperControllerRoutes[$name],[]), $parameters, $absolute);
        }

        if (! is_null($route = $this->routes->getByName($name))) {
            return $this->toRoute($route, $parameters, $absolute);
        }

        throw new RouteNotFoundException("Route [{$name}] not defined.");
    }

}