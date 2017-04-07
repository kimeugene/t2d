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
            'path' => '/var/www/logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // database
        'db' => [
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'database' => 't2d',
            'username' => 'root',
            'password' => '',
            'collation' => 'utf8_general_ci',
            'charset'   => 'utf8',
            'prefix' => ''
        ],


        'auth_code_ttl' => 60 * 60 * 24 * 14,
        'max_plates_allowed' => 2
    ],
];
