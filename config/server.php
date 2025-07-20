<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Server Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the server configuration for the application.
    | It ensures the application runs on port 8000 consistently.
    |
    */

    'port' => env('PORT', 8000),
    'host' => env('HOST', '0.0.0.0'),

    /*
    |--------------------------------------------------------------------------
    | Development Server
    |--------------------------------------------------------------------------
    |
    | Configuration for the development server.
    |
    */

    'dev_server' => [
        'port' => 8000,
        'host' => '0.0.0.0',
    ],

    /*
    |--------------------------------------------------------------------------
    | Production Server
    |--------------------------------------------------------------------------
    |
    | Configuration for the production server.
    |
    */

    'production_server' => [
        'port' => 8000,
        'host' => '0.0.0.0',
    ],
];