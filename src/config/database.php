<?php

return [
    // app
    'app' => [
        'read' => [
            'host' => getenv('DB_R_HOST'),
            'username' => getenv('DB_R_USERNAME'),
            'password' => getenv('DB_R_PASSWORD'),
            'port' => getenv('DB_R_PORT'),
            'database' => getenv('DB_R_DATABASE'),
        ],
        'write' => [
            'host' => getenv('DB_HOST'),
            'username' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD'),
            'port' => getenv('DB_PORT'),
            'database' => getenv('DB_DATABASE'),
        ],
        'driver' => 'mysql',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
    ],
    'report' => [
        'host' => getenv('REPORT_DB_HOST'),
        'driver' => 'mysql',
        'database' => getenv('REPORT_DB_DATABASE'),
        'username' => getenv('REPORT_DB_USERNAME'),
        'password' => getenv('REPORT_DB_PASSWORD'),
        'port' => getenv('REPORT_DB_PORT'),
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
    ]

];