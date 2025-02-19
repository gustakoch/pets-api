<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Http\View;

use OpenApi\Attributes as OA;
use App\User\Domain\Collection\Permissions;
use App\User\Domain\ValueObject\Permission;
use App\Shared\Infrastructure\Http\View\View;

final class RoleView implements View, \JsonSerializable
{
    #[OA\Property(type: 'array', items: new OA\Items(type: 'string'), example: Permissions::AVAILABLE)]
    public array $permissions;

    public function __construct(
        public string $id,
        public string $name,
        Permissions $permissions,
        public \DateTime $createdAt,
    ) {
        $this->permissions = array_map(
            static fn (Permission $permission) => $permission->value(),
            $permissions->toArray()
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'permissions' => $this->permissions,
        ];
    }
}
