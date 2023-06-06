<?php

namespace Gianfriaur\HyperController\Exception;

use Throwable;

class HyperControllerMissingControllerAnnotationException extends HyperControllerException
{
    public function __construct($controller, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            "your HyperController($controller) does not have the #[Controller(path: 'path' ,alias: 'my.alias')] annotation",
            $code, $previous);
    }
}