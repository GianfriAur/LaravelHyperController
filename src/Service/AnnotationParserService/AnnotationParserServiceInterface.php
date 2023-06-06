<?php

namespace Gianfriaur\HyperController\Service\AnnotationParserService;

use Gianfriaur\HyperController\Describer\HyperControllerDescriber;
use Illuminate\Foundation\Application;

interface AnnotationParserServiceInterface
{
    public function __construct(Application $app, array $options);

    function getHyperControllerDescriber($className): HyperControllerDescriber;
}