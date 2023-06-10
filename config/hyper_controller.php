<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Resolver
    |--------------------------------------------------------------------------
    |
    | it is the service that resolves the routes after laravel identifies the
    |   main one
    |
    */
    'resolver' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Macro Provider
    |--------------------------------------------------------------------------
    |
    | is the service that takes care of registering the routes of the library
    |   in the routes of laravel
    |
    */
    'macro_provider' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Annotation Parser
    |--------------------------------------------------------------------------
    |
    | is the service that takes care of interpreting the annotations and
    |   transforming them into the describers that the library uses in its logic
    |
    */
    'annotation_parser' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Cache Service
    |--------------------------------------------------------------------------
    |
    | is the service that takes care of saving the results of other services
    |   to increase performance
    |
    */
    'cache_service' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Resolvers
    |--------------------------------------------------------------------------
    |
    | list of all resolver available for the library
    |
    */
    'resolvers' => [
        'default' => [
            'class' => \Gianfriaur\HyperController\Service\ResolverService\HyperControllerDependencyResolver::class,
            'options' => []
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Macro Providers
    |--------------------------------------------------------------------------
    |
    | list of all macro provider available for the library
    |
    */
    'macro_providers' => [
        'default' => [
            'class' => \Gianfriaur\HyperController\Service\MacroProvider\DefaultMacroProvider::class,
            'options' => []
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Annotation Parsers
    |--------------------------------------------------------------------------
    |
    | list of all annotation parser available for the library
    |
    */
    'annotation_parsers' => [
        'default' => [
            'class' => \Gianfriaur\HyperController\Service\AnnotationParserService\DefaultAnnotationParserService::class,
            'options' => []
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Services
    |--------------------------------------------------------------------------
    |
    | list of all cache service available for the library
    |
    */
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
