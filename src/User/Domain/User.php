<?php

declare(strict_types=1);

namespace App\User\Domain;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Shared\Domain\Timestampable;
use App\User\Domain\ValueObject\UserId;
use App\User\Domain\ValueObject\Password;
use App\User\Domain\ValueObject\Permission;
use App\User\Domain\ValueObject\UserStatus;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity]
#[ORM\Table('users')]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: 'email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use Timestampable;

    #[ORM\Embedded(class: UserId::class, columnPrefix: false)]
    private UserId $id;

    #[ORM\Column(length: 255, type: Types::STRING)]
    private string $firstname;

    #[ORM\Column(length: 255, type: Types::STRING)]
    private string $lastname;

    #[ORM\Column(length: 255, type: Types::STRING, unique: true)]
    private string $email;

    #[ORM\Embedded(class: Password::class, columnPrefix: false)]
    private Password $password;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private Role $role;

    #[ORM\Column(length: 255)]
    private UserStatus $status;

    public function __construct()
    {
        $this->id = new UserId();
        $this->initializeTimestampable();
    }

    public static function register(
        string $firstname,
        string $lastname,
        string $email,
        Password $password,
        Role $role,
    ): self {
        $user = new self();
        $user->firstname = $firstname;
        $user->lastname = $lastname;
        $user->email = $email;
        $user->password = $password;
        $user->role = $role;
        $user->status = UserStatus::Active;

        return $user;
    }

    public function updateFirstname(string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function updateLastname(string $lastname): self
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function updateRole(Role $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function role(): Role
    {
        return $this->role;
    }

    public function getFullName(): string
    {
        return \sprintf('%s %s', $this->firstname, $this->lastname);
    }

    public function status(): UserStatus
    {
        return $this->status;
    }

    public function updatedStatus(UserStatus $status): void
    {
        $this->status = $status;
    }

    public function isPasswordExpired(): bool
    {
        return $this->password->isExpired();
    }

    public function getPassword(): ?string
    {
        return $this->password->hash();
    }

    // This method is mandatory for UserInterface implementation
    public function getRoles(): array
    {
        $permissions = array_map(static function (Permission $permission): string {
            return $permission->value();
        }, $this->role->permissions()->toArray());

        return array_unique($permissions);
    }

    public function eraseCredentials(): void
    {
        // This method is mandatory for UserInterface implementation
    }

    public function getUserIdentifier(): string
    {
        return $this->email();
    }
}
