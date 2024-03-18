<?php

namespace App\entities;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
#[ORM\HasLifecycleCallbacks]
class Users
{
    #[ORM\Id, ORM\Column(type: 'integer'), ORM\GeneratedValue]
    private int|null $id = null;

    #[ORM\Column(type: 'string')]
    private string|null $username = null;

    #[ORM\Column(type: 'string')]
    private string|null $password_hash = null;

    /**
     * Many Users have Many Groups.
     * @var Collection<int, Groups>
     */
    #[ManyToMany(targetEntity: Groups::class, inversedBy: 'users')]
    #[JoinTable(name: 'user_groups')]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[InverseJoinColumn(name: 'group_id', referencedColumnName: 'id')]
    private Collection $groups;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @return string|null
     */
    public function getPasswordHash(): ?string
    {
        return $this->password_hash;
    }

    /**
     * @return Collection
     */
    public function getGroups(): ArrayCollection|Collection
    {
        return $this->groups;
    }

    public function getGroupsAsArray(): array
    {
        $groups = [];
        foreach ($this->getGroups() as $group) {
            /* @var Groups $group */
            $permissions = $group->getPermissionsAsArray();
            $groups[] = [
                "id" => $group->getId(),
                "name" => $group->getName(),
                "permissions" => !empty($permissions) ? $permissions : false,
            ];
        }

        return $groups;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}
