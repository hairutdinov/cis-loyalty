<?php

namespace App\entities;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;

#[ORM\Entity]
#[ORM\Table(name: 'permissions')]
#[ORM\HasLifecycleCallbacks]
class Permissions
{
    #[ORM\Id, ORM\Column(type: 'integer'), ORM\GeneratedValue]
    private int|null $id = null;

    #[ORM\Column(type: 'string')]
    private string|null $name = null;

    /**
     * Many Permissions have Many Groups.
     * @var Collection<int, Groups>
     */
    #[ManyToMany(targetEntity: Groups::class, inversedBy: 'permissions')]
    #[JoinTable(name: 'group_permissions')]
    #[JoinColumn(name: 'permission_id', referencedColumnName: 'id')]
    #[InverseJoinColumn(name: 'group_id', referencedColumnName: 'id')]
    private Collection $groups;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return Collection
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function setId(?int $id): Permissions
    {
        $this->id = $id;
        return $this;
    }

    public function setName(?string $name): Permissions
    {
        $this->name = $name;
        return $this;
    }
}
