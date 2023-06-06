<?php

namespace Gianfriaur\HyperController\Exception;

use Throwable;

class HyperControllerAliasAnnotationException extends HyperControllerException
{
    public function __construct($controller, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            "you cannot give aliases to routes if the controller($controller) does not have an alias ",
            $code, $previous);
    }
}