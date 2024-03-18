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

if (preg_match('/^\/api\/users\/(?P<user_id>\d+)\/groups$/', $path, $matches)) {
    if ($method === 'GET') {
        $user_id = $matches["user_id"] ?? null;

        $user = $entityManager->find(Users::class, $user_id);

        if (empty($user)) {
            http_response_code(400);
            echo json_encode([
                'error' => 'Пользователь не найден',
            ]);
            exit();
        }

        $groups = $user->getGroupsAsArray();

        echo json_encode(['id' => $user->getId(), 'username' => $user->getUsername(), 'groups' => !empty($groups) ? $groups : false], JSON_UNESCAPED_UNICODE);
        exit();
    }
} elseif (preg_match('/^\/api\/groups\/(?P<group_id>\d+)\/users\/(?P<user_id>\d+)$/', $path, $matches)) {
    if ($method === 'POST') {
        $user_id = $matches["user_id"] ?? null;
        $group_id = $matches["group_id"] ?? null;

        $group = $entityManager->find(Groups::class, $group_id);

        if (empty($group)) {
            http_response_code(400);
            echo json_encode([
                'error' => 'Группа не найдена',
            ]);
            exit();
        }

        $user = $entityManager->find(Users::class, $user_id);

        if (empty($user)) {
            http_response_code(400);
            echo json_encode([
                'error' => 'Пользователь не найден',
            ]);
            exit();
        }

        $group->addUser($user);

        $entityManager->persist($group);
        $entityManager->flush();
        exit();
    } elseif ($method === 'DELETE') {
        exit();
    }
}

http_response_code(404);
echo json_encode(['error' => 'Not Found']);