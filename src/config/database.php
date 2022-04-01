<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', 'srv-db'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'stripe'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', 'passwd'),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => env('DB_PREFIX', ''),
            'strict' => env('DB_STRICT_MODE', true),
            'engine' => env('DB_ENGINE', 'InnoDB'),
            'timezone' => env('DB_TIMEZONE', '+00:00'),
        ],
        'testing' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => ''
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

//REDIS_MASTER_HOST=srv-redis.backend
//REDIS_MASTER_PORT=6379
//REDIS_MASTER_PASSWORD=

//    'redis' => [
//
//        'client' => env('REDIS_CLIENT', 'phpredis'),
//
//        'cluster' => env('REDIS_CLUSTER', false),
//
//        'default' => [
//            'host' => env('REDIS_MASTER_HOST', '127.0.0.1'),
//            'password' => env('REDIS_MASTER_PASSWORD', null),
//            'port' => env('REDIS_MASTER_PORT', 6379),
//            'database' => env('REDIS_DB', 0),
//        ],
//
//        'cache' => [
//            'host' => env('REDIS_HOST', '127.0.0.1'),
//            'password' => env('REDIS_PASSWORD', null),
//            'port' => env('REDIS_PORT', 6379),
//            'database' => env('REDIS_CACHE_DB', 1),
//        ],
//
//    ],

];
