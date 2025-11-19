<?php

return [
    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    |
    | This is the User model used by the package. You can change this to
    | your own User model if needed.
    |
    */
    'user_model' => \App\Models\User::class,

    /*
    |--------------------------------------------------------------------------
    | Route Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the routes for the wallet package.
    |
    */
    'route_prefix' => 'api/wallet',
    'middleware' => ['api'],

    /*
    |--------------------------------------------------------------------------
    | Currency Configuration
    |--------------------------------------------------------------------------
    |
    | Default currency for wallets.
    |
    */
    'default_currency' => 'USD',

    /*
    |--------------------------------------------------------------------------
    | Decimal Places
    |--------------------------------------------------------------------------
    |
    | Number of decimal places for wallet balances.
    |
    */
    'decimal_places' => 2,
];

