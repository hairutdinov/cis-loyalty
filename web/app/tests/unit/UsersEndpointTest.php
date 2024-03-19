<?php

namespace AppTest\unit;

use App\entities\Groups;
use App\entities\Permissions;
use App\entities\Users;
use App\Foo;
use AppTest\DatabaseTestCase;
use GuzzleHttp\Client;
use Phinx\Console\PhinxApplication;


class UsersEndpointTest extends DatabaseTestCase
{
    public function testGetUserGroupsAndPermission()
    {
        $user_data = [
            'id' => 1,
            'username' => 'test_user',
        ];

        $group_data = [
            'id' => 1,
            'name' => 'test_group',
        ];

        $permission_data = [
            'id' => 1,
            'name' => 'test_permission',
        ];

        $user = new Users();
        $user->setId($user_data["id"])
            ->setUsername($user_data["username"]);

        $group = new Groups();
        $group->setId($group_data["id"])
            ->setName($group_data["name"]);

        $permission = new Permissions();
        $permission->setId($permission_data["id"])
            ->setName($permission_data["name"]);

        $this->entity_manager->persist($user);
        $this->entity_manager->persist($permission);

        $group->addUser($user);
        $group->addPermission($permission);

        $this->entity_manager->persist($group);
        $this->entity_manager->flush();


        $response = $this->client->get('/api/users/' . $user_data['id'] . '/groups');

        $expected = [
            "id" => $user_data["id"],
            "username" => $user_data["username"],
            "groups" => [
                [
                    "id" => $group_data["id"],
                    "name" => $group_data["name"],
                    "permissions" => [
                        [
                            "id" => $permission_data["id"],
                            "name" => $permission_data["name"],
                        ]
                    ]
                ]
            ]
        ];

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expected, json_decode($response->getBody(), 1));
    }

    public function testGetUserWithNoGroups()
    {
        $user_data = [
            'id' => 1,
            'username' => 'test_user',
        ];

        $user = new Users();
        $user->setId($user_data["id"])
            ->setUsername($user_data["username"]);

        $this->entity_manager->persist($user);

        $this->entity_manager->flush();

        $response = $this->client->get('/api/users/' . $user_data['id'] . '/groups');

        $expected = [
            "id" => $user_data["id"],
            "username" => $user_data["username"],
            "groups" => false
        ];

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expected, json_decode($response->getBody(), 1));
    }

    public function testGetUserWithGroupsWithNoPermissions()
    {
        $user_data = [
            'id' => 1,
            'username' => 'test_user',
        ];

        $group_data = [
            'id' => 1,
            'name' => 'test_group',
        ];

        $user = new Users();
        $user->setId($user_data["id"])
            ->setUsername($user_data["username"]);

        $group = new Groups();
        $group->setId($group_data["id"])
            ->setName($group_data["name"]);

        $this->entity_manager->persist($user);

        $group->addUser($user);

        $this->entity_manager->persist($group);
        $this->entity_manager->flush();

        $response = $this->client->get('/api/users/' . $user_data['id'] . '/groups');

        $expected = [
            "id" => $user_data["id"],
            "username" => $user_data["username"],
            "groups" => [
                [
                    "id" => $group_data["id"],
                    "name" => $group_data["name"],
                    "permissions" => false
                ]
            ]
        ];

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expected, json_decode($response->getBody(), 1));
    }
}
