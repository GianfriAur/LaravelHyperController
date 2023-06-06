<?php

namespace Gianfriaur\HyperController\Service\MacroProvider;

use Gianfriaur\PackageLoader\Service\PackageProviderService\PackageProviderServiceInterface;
use Illuminate\Foundation\Application;
use Packages\Core\Http\Controllers\HyperController;

interface MacroProviderInterface
{
    public function __construct(Application $app, array $options);

    function registerMacros(): void;
}