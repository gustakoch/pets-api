<?php

declare(strict_types=1);

namespace App\User\Domain;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Shared\Domain\Timestampable;
use App\User\Domain\ValueObject\RoleId;
use App\User\Domain\Collection\Permissions;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table('roles')]
#[ORM\HasLifecycleCallbacks]
class Role
{
    use Timestampable;

    public const ADMIN = 'admin';
    public const USER = 'user';
    public const GUEST = 'guest';

    #[ORM\Embedded(class: RoleId::class, columnPrefix: false)]
    private RoleId $id;

    #[ORM\Column(length: 255, type: Types::STRING, unique: true)]
    private string $name;

    #[ORM\Column(type: 'permissions')]
    private Permissions $permissions;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'role')]
    private Collection $users;

    public function __construct()
    {
        $this->id = new RoleId();
        $this->users = new ArrayCollection();
        $this->initializeTimestampable();
    }

    public static function create(
        string $name,
        Permissions $permissions,
    ): self {
        $role = new self();
        $role->name = $name;
        $role->permissions = $permissions;

        return $role;
    }

    public function getId(): RoleId
    {
        return $this->id;
    }

    public function permissions(): Permissions
    {
        return $this->permissions;
    }

    public function updateName(string $name): void
    {
        $this->name = $name;
    }

    public function updatePermissions(Permissions $permissions): void
    {
        $this->permissions = $permissions;
    }
}
