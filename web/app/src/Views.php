<?php

namespace App;

use App\entities\Groups;
use App\entities\Users;
use Doctrine\ORM\EntityManager;

class Views
{
    public static function get_user_view(int $user_id, EntityManager $em)
    {
        $user = $em->find(Users::class, $user_id);

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

    public static function add_user_to_group(int $user_id, int $group_id, EntityManager $em)
    {
        $group = $em->find(Groups::class, $group_id);

        if (empty($group)) {
            http_response_code(400);
            echo json_encode([
                'error' => 'Группа не найдена',
            ]);
            exit();
        }

        $user = $em->find(Users::class, $user_id);

        if (empty($user)) {
            http_response_code(400);
            echo json_encode([
                'error' => 'Пользователь не найден',
            ]);
            exit();
        }

        $group->addUser($user);

        $em->flush();
        exit();
    }

    public static function delete_user_from_group(int $user_id, int $group_id, EntityManager $em)
    {
        $group = $em->find(Groups::class, $group_id);

        if (empty($group)) {
            http_response_code(400);
            echo json_encode([
                'error' => 'Группа не найдена',
            ]);
            exit();
        }

        $user = $em->find(Users::class, $user_id);

        if (empty($user)) {
            http_response_code(400);
            echo json_encode([
                'error' => 'Пользователь не найден',
            ]);
            exit();
        }

        $group->removeUser($user);

        $em->flush();
        exit();
    }
}