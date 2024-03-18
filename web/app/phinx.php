<?php

require 'vendor/autoload.php';
require 'bootstrap.php';


return
    [
        'paths' => [
            'migrations' => __DIR__ . '/migrations',
            'seeds' => __DIR__ . '/seeds'
        ],
        'environments' => [
            'default_migration_table' => 'phinxlog',
            'default_environment' => 'development',
            'development' => [
                'adapter' => 'mysql',
                'host' => $_ENV['MYSQL_HOST'] ?? '',
                'name' => $_ENV['MYSQL_DATABASE'] ?? '',
                'user' => $_ENV['MYSQL_ROOT_USER'] ?? 'root',
                'pass' => $_ENV['MYSQL_ROOT_PASSWORD'] ?? '',
                'port' => $_ENV['MYSQL_EXPOSE_PORT'] ?? '',
                'charset' => 'utf8',
            ],
        ],
        'version_order' => 'creation'
    ];
