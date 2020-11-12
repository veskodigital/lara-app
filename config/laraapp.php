<?php

return [

    /*
    |--------------------------------------------------------------------------
    | LaraApp User model
    |--------------------------------------------------------------------------
    |
    | Here you can set a user model you want to oberseve from the LaraApp. 
    | E.g. recevie notifications when a new user signs up on your smartphone.
    |
    */

    'user' => App\User::class,


     /*
    |--------------------------------------------------------------------------
    | LaraApp Path
    |--------------------------------------------------------------------------
    |
    | This is the URI path where LaraApp will be accessible from.
    | Note that the URI will not affect the paths of its internal API that aren't exposed to users.
    |
    */

    'path' => env('LA_PATH', 'lara-app'),

     /*
    |--------------------------------------------------------------------------
    | LaraApp Domain
    |--------------------------------------------------------------------------
    |
    | This is the subdomain where LaraApp can be accessible from. Please note, If this
    | setting is null, LaraApp will reside under the same domain as the
    | application. Otherwise, this value will serve as the subdomain.
    |
    */

    'domain' => null,

    /*
    |--------------------------------------------------------------------------
    | LaraApp Route Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will get attached onto each LaraApp route. If you 
    | want to add your own middleware to this list, you can attached them below.
    |
    */

    'middleware' => ['web'],


    /*
    |--------------------------------------------------------------------------
    | LaraApp Storage Path
    |--------------------------------------------------------------------------
    |
    | This is the path to your logs directory
    | By default this will be storage/logs
    |
    */

    'storage_path' => 'storage/logs',

    /*
    |--------------------------------------------------------------------------
    | LaraApp App Name
    |--------------------------------------------------------------------------
    |
    | This is the name which will be displayed in the app
    | By default this is set to your ENV APP_NAME
    |
    */

    'app_name' => env('APP_NAME', 'Laravel App'),

     /*
    |--------------------------------------------------------------------------
    | LaraApp App Key
    |--------------------------------------------------------------------------
    |
    | Here you can set the application app key from https://thelara.app/
    |
    */

    'appkey' => '',

    /*
    |--------------------------------------------------------------------------
    | LaraApp User Observer Notifications
    |--------------------------------------------------------------------------
    |
    | LaraApp can observer your Users model and can notify you everytime a new created user has joined.
    | Set below if you wish to use this feature
    |
    */

    'observer' => [
        'should_observe' => true,
        'newUsers' => true, // When created gets called on the observer model
    ],

    /*
    |--------------------------------------------------------------------------
    | LaraApp Logging
    |--------------------------------------------------------------------------
    |
    | If default is set to stack, single or daily then the app will be able to return the logs.
    | This will automatically come from the env setting in your application
    |
    */

    'logging_default' => env('LOG_CHANNEL', 'stack'),

];