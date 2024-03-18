<?php

require_once "../app/vendor/autoload.php";

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: array(__DIR__."/entities"),
    isDevMode: true,
);

$connection = DriverManager::getConnection([
    'driver'   => 'pdo_mysql',
    'host'     => $_ENV['MYSQL_HOST'] ?? '',
    'user'     => $_ENV['MYSQL_ROOT_USER'] ?? 'root',
    'password' => $_ENV['MYSQL_ROOT_PASSWORD'] ?? '',
    'dbname'   => $_ENV['MYSQL_DATABASE'] ?? '',
    'charset' => 'utf8',
], $config);

$entityManager = new EntityManager($connection, $config);
