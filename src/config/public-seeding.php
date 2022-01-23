<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Public Seeding Path
    |--------------------------------------------------------------------------
    |
    | This is the URI path where Laravel Pubic Seeding will locate its internal API routes.
    |
    */

    'path' => '_public-seeding',

    // TODO add description
    'models_namespace' => 'App',

    'middleware' => ['api', 'guest:api'],

    'allow_env' => ['local'],
];
