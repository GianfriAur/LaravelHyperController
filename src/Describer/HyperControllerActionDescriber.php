<?php

namespace Gianfriaur\HyperController\Describer;

use ReflectionException;
use ReflectionMethod;

readonly class HyperControllerActionDescriber
{
    public function __construct(
        public string $partialPath,
        public string $fullPath,
        public string $partialAlias,
        public string $fullAlias,
        public string $reflectedPointer,
        public bool $index,
        public array $methods,
        public array $middlewares,
        public array $withoutMiddlewares,
    )
    {
    }

    /**
     * @throws ReflectionException
     */
    public function getReflection():ReflectionMethod{
        [ $class,$action ] = explode('@',$this->reflectedPointer);
        return new ReflectionMethod( $class,$action);
    }

    public function getControllerClassName():string{
        [ $class,$action ] = explode('@',$this->reflectedPointer);
        return  $class;
    }

    public function getMethodName():string{
        [ $class,$action ] = explode('@',$this->reflectedPointer);
        return $action;
    }

    public function getNormalizedPartialPath($forExtraction = false):string{
        $path = $this->partialPath;
        $matches = $this->getPartialPathParameters();

        if (sizeof($matches)>0){
            foreach ($matches as $match){
                $path = str_replace($match,$forExtraction ?'(\b(?!/b)\w+)' : '\b(?!/b)\w+'  ,$path);
            }
        }
        return $path;
    }

    public function getPartialPathParameters():array{
        $path = $this->partialPath;
        preg_match_all('/{[^!\/]*}/m', $path, $matches, PREG_SET_ORDER, 0);
        return array_reduce($matches, fn($carry, $array) =>array_merge($carry, $array), []);
    }

    public function getNormalizedFullPath($forExtraction = false):string{
        $path = $this->fullPath;
        $matches = $this->getPartialPathParameters();

        if (sizeof($matches)>0){
            foreach ($matches as $match){
                $path = str_replace($match,$forExtraction ?'(\b(?!/b)\w+)' : '\b(?!/b)\w+',$path);
            }
        }
        return $path;
    }

    public function getFullPathParameters():array{
        $path = $this->fullPath;
        preg_match_all('/{[^!\/]*}/m', $path, $matches, PREG_SET_ORDER, 0);
        return array_reduce($matches, fn($carry, $array) =>array_merge($carry, $array), []);
    }
}