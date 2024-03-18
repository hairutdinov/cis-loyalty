<?php
require_once "../app/bootstrap.php";

use App\entities\Groups;
use App\entities\Users;
use Doctrine\ORM\EntityManager;

/* @var $entityManager EntityManager */

header('Content-Type: application/json; charset=UTF-8');

$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);

$method = $_SERVER['REQUEST_METHOD'];

if (preg_match('/^\/api\/users\/(\d+)\/groups$/', $path, $matches) && $method === 'GET') {
    $user_id = $matches[1];

    $user = $entityManager->find(Users::class, $user_id);

    $groups = $user->getGroupsAsArray();

    echo json_encode(['id' => $user->getId(), 'username' => $user->getUsername(), 'groups' => !empty($groups) ? $groups : false], JSON_UNESCAPED_UNICODE);
    exit();
}