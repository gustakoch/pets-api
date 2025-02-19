<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Http\View;

use App\User\Domain\Collection\Permissions;
use App\User\Domain\ValueObject\Permission;
use App\User\Domain\ValueObject\UserStatus;
use App\Shared\Infrastructure\Http\View\View;

final class UserView implements View, \JsonSerializable
{
    public array $role;

    public function __construct(
        public string $id,
        public string $firstname,
        public string $lastname,
        public string $email,
        public UserStatus $status,
        public string $roleName,
        Permissions $permissions,
        public \DateTime $createdAt,
    ) {
        $this->role = [
            'name' => $this->roleName,
            'permissions' => array_map(
                static fn (Permission $permission) => $permission->value(),
                $permissions->toArray()
            ),
        ];
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'status' => $this->status->value(),
            'role' => $this->role,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
