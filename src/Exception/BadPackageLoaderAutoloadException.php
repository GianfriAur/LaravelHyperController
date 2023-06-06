<?php

namespace Gianfriaur\HyperController\Exception;

use Gianfriaur\HyperController\HyperControllerServiceProvider;
use Throwable;

class BadPackageLoaderAutoloadException extends HyperControllerException
{
    public function __construct(int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            "It seems that it was not possible to autoload in the PackageLoader, make sure you installed it via 'composer require gianfriaur/package-loader', otherwise you can set the ".HyperControllerServiceProvider::CONFIG_FILE_NANE.".package_loader_autoload setting to false or 'auto'",
            $code,
            $previous
        );
    }
}