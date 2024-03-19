<?php

namespace AppTest\unit;

use App\entities\Groups;
use App\entities\Permissions;
use App\entities\Users;
use App\Foo;
use AppTest\DatabaseTestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use http\Client\Curl\User;
use Phinx\Console\PhinxApplication;


class GroupsEndpointTest extends DatabaseTestCase
{
    public function testAddingUserToGroup()
    {
        $user_data = [
            'id' => 1,
            'username' => 'test_user_1',
        ];

        $group_data = [
            'id' => 1,
            'name' => 'test_group_1',
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

        $group->addPermission($permission);

        $this->entity_manager->persist($group);
        $this->entity_manager->flush();


        $url = '/api/groups/' . $group->getId() . '/users/' . $user->getId();
        $response = $this->client->post($url);

        $expected = [
            [
                "id" => $group->getId(),
                "name" => $group_data["name"],
                "permissions" => [
                    [
                        "id" => $permission_data["id"],
                        "name" => $permission_data["name"],
                    ]
                ]
            ]
        ];

        $this->assertEquals(201, $response->getStatusCode());

        $user_repository = $this->entity_manager->getRepository(Users::class);
        $user = $user_repository->findOneBy(['username' => $user_data["username"]]);
        $this->entity_manager->refresh($user);
        $this->assertEquals($user_data['username'], $user->getUsername());
        $this->assertEquals($expected, $user->getGroupsAsArray());
    }

    public function testAddingUserToNonExistingGroup()
    {
        $user_data = [
            'id' => 1,
            'username' => 'test_user_2',
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

        $group->addPermission($permission);

        $this->entity_manager->persist($group);
        $this->entity_manager->flush();

        $non_exising_group_id = 2;

        $expected = [
            'error' => 'Группа не найдена',
        ];

        try {
            $this->client->post('/api/groups/' . $non_exising_group_id . '/users/' . $user_data['id']);
        } catch (ClientException $e) {
            $this->assertEquals(400, $e->getResponse()->getStatusCode());
            $this->assertEquals($expected, json_decode($e->getResponse()->getBody(), 1));
        }
    }

    public function testDeletingUserFromGroup()
    {
        $user_data = [
            'id' => 1,
            'username' => 'test_user_1',
        ];

        $group_data = [
            'id' => 1,
            'name' => 'test_group_1',
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

        $group->addPermission($permission);
        $group->addUser($user);

        $this->entity_manager->persist($group);
        $this->entity_manager->flush();


        $url = '/api/groups/' . $group->getId() . '/users/' . $user->getId();
        $response = $this->client->delete($url);

        $this->assertEquals(200, $response->getStatusCode());

        $user_repository = $this->entity_manager->getRepository(Users::class);
        $user = $user_repository->findOneBy(['username' => $user_data["username"]]);
        $this->entity_manager->refresh($user);
        $this->assertEquals($user_data['username'], $user->getUsername());
        $this->assertEmpty($user->getGroupsAsArray());
    }
}
