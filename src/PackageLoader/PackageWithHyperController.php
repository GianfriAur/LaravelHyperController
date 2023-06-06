<?php

namespace Gianfriaur\HyperController\PackageLoader;

interface PackageWithHyperController
{
    /**
     * @return array<class-string>
     */
    public function getHyperControllers(): array;
}