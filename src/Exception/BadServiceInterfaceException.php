<?php

namespace Gianfriaur\HyperController\Exception;

use Throwable;

class BadServiceInterfaceException extends HyperControllerException
{
    public function __construct(string $strategy, string $providedService = "",string $expectedInterface = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            "The supplied $strategy($providedService) does not implement $expectedInterface",
            $code,
            $previous
        );
    }
}