<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Ziggy Configuration
    |--------------------------------------------------------------------------
    |
    | The configuration file for the Ziggy package allows you to specify
    | which route groups should be available to your JavaScript application.
    |
    */

    'groups' => [
        'web' => [
            'middleware' => ['web'],
        ],
        'admin' => [
            'middleware' => ['auth'],
            'prefix' => 'admin',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Skipped Route Names
    |--------------------------------------------------------------------------
    |
    | Here you may specify route names that should be skipped when building
    | the Ziggy route list. This is useful for routes that should not be
    | accessible from your JavaScript application.
    |
    */

    'skip' => [
        'debugbar.*',
        'horizon.*',
        'telescope.*',
        'passport.*',
        'sanctum.*',
    ],

    /*
    |--------------------------------------------------------------------------
    | Route Filter
    |--------------------------------------------------------------------------
    |
    | Here you may specify a filter that will be applied to all routes before
    | they are added to the Ziggy route list. This is useful for filtering out
    | routes that should not be accessible from your JavaScript application.
    |
    */

    'filter' => null,

    /*
    |--------------------------------------------------------------------------
    | Route List Output
    |--------------------------------------------------------------------------
    |
    | Here you may specify where the Ziggy route list should be output. By
    | default, the route list will be output to the `@routes` Blade directive.
    |
    */

    'output' => null,

    /*
    |--------------------------------------------------------------------------
    | Route List Namespace
    |--------------------------------------------------------------------------
    |
    | Here you may specify the namespace that should be used when generating
    | the Ziggy route list. This is useful for applications that use a custom
    | namespace for their routes.
    |
    */

    'namespace' => null,

    /*
    |--------------------------------------------------------------------------
    | Route List Domain
    |--------------------------------------------------------------------------
    |
    | Here you may specify the domain that should be used when generating the
    | Ziggy route list. This is useful for applications that use a custom
    | domain for their JavaScript application.
    |
    */

    'domain' => null,
];
