<?php

namespace Gianfriaur\HyperController\Exception;

use Gianfriaur\HyperController\HyperControllerServiceProvider;
use Throwable;

class HyperControllerMissingConfigException extends HyperControllerException
{
    public function __construct(string $config_name = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            "HyperController cannot load services, $config_name config in file 'config/" . HyperControllerServiceProvider::CONFIG_FILE_NANE . '\'',
            $code, $previous);
    }

}