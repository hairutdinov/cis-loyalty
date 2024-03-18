<?php
require_once "../app/bootstrap.php";

use App\ApiRouter;
use App\entities\Groups;
use App\entities\Users;
use App\Route;
use App\Views;
use Doctrine\ORM\EntityManager;

/* @var $entityManager EntityManager */

header('Content-Type: application/json; charset=UTF-8');

$router = new ApiRouter($entityManager);
$router->addRoute(new Route('/^\/api\/users\/(?P<user_id>\d+)\/groups$/', [Views::class, 'get_user_view']));
$router->addRoute(new Route('/^\/api\/groups\/(?P<group_id>\d+)\/users\/(?P<user_id>\d+)$/', [Views::class, 'add_user_to_group'], 'POST'));
$router->addRoute(new Route('/^\/api\/groups\/(?P<group_id>\d+)\/users\/(?P<user_id>\d+)$/', [Views::class, 'delete_user_from_group'], 'DELETE'));
$router->handleRequest();