<?php

namespace Gianfriaur\HyperController\Service\MacroProvider;

use Illuminate\Foundation\Application;

interface MacroProviderInterface
{
    public function __construct(Application $app, array $options);

    function registerMacros(): void;
}