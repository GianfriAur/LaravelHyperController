<?php

namespace Gianfriaur\HyperController\Service\ResolverService;

use Gianfriaur\HyperController\Describer\HyperControllerActionDescriber;
use Gianfriaur\HyperController\Http\Controllers\HyperController;
use Illuminate\Foundation\Application;

interface HyperControllerDependencyResolverInterface
{
    public function __construct(Application $app, array $options);

    function resolveHyperController(HyperController $screen, HyperControllerActionDescriber $controllerActionDescriber,array $httpQueryArguments = []): array;

    public function extractHyperControllerActionDescriber(HyperController $screen, ?string $method) :HyperControllerActionDescriber;
}