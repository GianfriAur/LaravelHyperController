<?php

namespace Gianfriaur\HyperController\Describer;

use Gianfriaur\HyperController\Enum\ActionMethodEnum;
use ReflectionClass;
use ReflectionException;

readonly class HyperControllerDescriber
{

    /**
     * @param string $basePath
     * @param string $baseAlias
     * @param bool $hasIndex
     * @param array<HyperControllerActionDescriber> $actions
     * @param array<ActionMethodEnum> $methods
     * @param string $class
     */
    public function __construct(
        public string $basePath,
        public string $baseAlias,
        public bool   $hasIndex,
        public array $actions,
        public array  $methods,
        public string $class,
        public array $middlewares,
        public array $skip_middlewares,
    )
    {
    }

    /**
     * @throws ReflectionException
     */
    public function getReflection():ReflectionClass{
        return new ReflectionClass($this->class);
    }
}