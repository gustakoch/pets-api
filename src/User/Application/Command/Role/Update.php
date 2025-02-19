<?php

declare(strict_types=1);

namespace App\User\Application\Command\Role;

use OpenApi\Attributes as OA;
use App\User\Domain\ValueObject\RoleId;
use App\User\Domain\Collection\Permissions;
use Symfony\Component\Serializer\Attribute\Ignore;
use App\User\Application\Validator\PermissionExist;
use Symfony\Component\Validator\Constraints as Assert;

final class Update
{
    #[Assert\NotBlank]
    public string $name;

    #[PermissionExist()]
    #[OA\Property(type: 'array', example: Permissions::AVAILABLE, items: new OA\Items(type: 'string'))]
    public array $permissions;

    public function __construct(
        #[Assert\NotBlank]
        #[Ignore]
        public RoleId $id,
    ) {
    }
}
