<?php

namespace Gianfriaur\HyperController\Service\ResolverService;

use Gianfriaur\HyperController\Describer\HyperControllerActionDescriber;
use Gianfriaur\HyperController\Http\Controllers\HyperController;
use Gianfriaur\HyperController\Service\AnnotationParserService\AnnotationParserServiceInterface;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use ReflectionClass;
use ReflectionParameter;
class HyperControllerDependencyResolver implements HyperControllerDependencyResolverInterface
{

    private AnnotationParserServiceInterface $annotationParserService;

    /** @noinspection PhpPropertyOnlyWrittenInspection */
    public function __construct(private Application $app, private array $options){
        $this->annotationParserService = $app->get(AnnotationParserServiceInterface::class);
    }

    function isRoute($action,$compare):bool{
        if ($compare === "") return false;

        preg_match_all('/^'.str_replace('/','\/',$compare).'$/', $action, $matches, PREG_SET_ORDER, 0);

        return sizeof($matches)>0;
    }


    public function extractHyperControllerActionDescriber(HyperController $screen, ?string $method) :HyperControllerActionDescriber {
        $selfDescriber = $this->annotationParserService->getHyperControllerDescriber($screen::class);

        if (!$method){
            if (!$selfDescriber->hasIndex){
                //TODO better exception
                throw new \Exception('Controller haven\'t index action');
            }else{
                $methodFunction = array_values(array_filter($selfDescriber->actions, fn($a)=>$a->partialPath===''))[0]??null;
            }
        }else{
            $methodFunction = array_values(array_filter($selfDescriber->actions, fn($a)=>$this->isRoute($method,$a->getNormalizedPartialPath())))[0]??null;
        }

        if (!$methodFunction){
            abort(404);
        }
        return $methodFunction;
    }


    /**
     * @throws \ReflectionException
     */
    function resolveHyperController(HyperController $screen, HyperControllerActionDescriber $controllerActionDescriber, array $httpQueryArguments = []): array
    {
        $parameters = $controllerActionDescriber->getReflection()->getParameters();

        $arguments = collect($httpQueryArguments);


        return  collect($parameters)
            ->map(function (ReflectionParameter $parameter) use (&$arguments, $controllerActionDescriber ) {
                return $this->bind($parameter, $arguments,$controllerActionDescriber);
            })
            ->all();

    }

    private function bind(ReflectionParameter $parameter, Collection $httpQueryArguments, HyperControllerActionDescriber $controllerActionDescriber)
    {



        $class = $parameter->getType() && ! $parameter->getType()->isBuiltin()
            ? $parameter->getType()->getName()
            : null;


        if ($class === null) {


            preg_match_all(
                '/^\/'.str_replace('/','\/',$controllerActionDescriber->getNormalizedFullPath(true)).'$/',
                request()->getRequestUri(),
                $matches,
                PREG_SET_ORDER,
                0
            );
            $params= $controllerActionDescriber->getFullPathParameters();

            foreach ($params as $i => $param) {
                if ($param === "{" . $parameter->name . "}") {
                    if ($parameter->getType() === 'int') {
                        return intval($matches[0][$i + 1]);
                    }
                    return $matches[0][$i + 1];
                }
            }
            return $httpQueryArguments->shift();
        }



        $instance = resolve($class);

        if (! is_a($instance, UrlRoutable::class)) {
            return $instance;
        }



        $value = $httpQueryArguments->shift();

        if ($value === null) {
            return $instance;
        }

        $model = $instance->resolveRouteBinding($value);

        throw_if(
            $model === null && ! $parameter->isDefaultValueAvailable(),
            (new ModelNotFoundException())->setModel($class, [$value])
        );

        optional(Route::current())->setParameter($parameter->getName(), $model);

        return $model;
    }
}
