<?php

require_once "vendor/autoload.php";

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: array(__DIR__."/entities"),
    isDevMode: true,
);

$config_file = ($_ENV['APP_ENV'] ?? 'development') === 'testing' ? 'config_test.php' : 'config.php';

require __DIR__ . "/$config_file";

$connection = DriverManager::getConnection([
    'driver'   => 'pdo_mysql',
    'host'     => $mysql_host,
    'user'     => $mysql_root_user,
    'password' => $mysql_root_password,
    'dbname'   => $mysql_database,
    'port'   => (int) $mysql_expose_port,
    'charset' => 'utf8',
], $config);

$entityManager = new EntityManager($connection, $config);
