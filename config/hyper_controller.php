<?php

return [

    'resolver' => 'default',

    'macro_provider' => 'default',

    'annotation_parser' => 'default',

    'cache_service' => 'default',

    'resolvers' => [
        'default' => [
            'class' => \Gianfriaur\HyperController\Service\ResolverService\HyperControllerDependencyResolver::class,
            'options' => []
        ]
    ],

    'macro_providers' => [
        'default' => [
            'class' => \Gianfriaur\HyperController\Service\MacroProvider\DefaultMacroProvider::class,
            'options' => []
        ]
    ],

    'annotation_parsers' => [
        'default' => [
            'class' => \Gianfriaur\HyperController\Service\AnnotationParserService\DefaultAnnotationParserService::class,
            'options' => []
        ]
    ],

    'cache_services' => [
        'default' => [
            'class' => \Gianfriaur\HyperController\Service\CacheService\DefaultCacheService::class,
            'options' => [
                'cache_file' => 'cache/hyper_controllers.php'
            ]
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Package Loader Autoload
    |--------------------------------------------------------------------------
    |
    | if the 'gianfriaur/package-loader' package is installed it automatically
    |     loads the resolver for each packet
    |     can be:
    |         'auto' => if it is not found, the package does not perform any
    |                       action otherwise it behaves like true
    |         true   => if it doesn't find the package, throw an exception,
    |                       otherwise perform the action
    |         false  => no action will be taken even if the
    |                       'gianfriaur/package-loader' is installed
    |
    */
    'package_loader_autoload' => 'auto',
];
