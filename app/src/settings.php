<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'determineRouteBeforeAppMiddleware' => true,
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => 'php://stdout',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // database
        'db' => [
            'driver' => 'mysql',
            'host' => 'local.db.cartexted.com',
            'database' => 't2d',
            'username' => 'root',
            'password' => 'root',
            'collation' => 'utf8_general_ci',
            'charset'   => 'utf8',
            'prefix' => ''
        ],

        'memcached' => [
            'host'  => 'local.cache.cartexted.com',
            'port'  => 11211
        ],

        'email_cache_ttl' => 60,
        'email_retry_attempts' => 2,


        'email_auth_code_ttl' => 60 * 60 * 24 * 14,
        'phone_auth_code_ttl' => 60 * 10,
        'max_plates_allowed' => 2
    ],
];
