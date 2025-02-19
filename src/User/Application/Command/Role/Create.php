<?php

declare(strict_types=1);

namespace App\User\Application\Command\Role;

use OpenApi\Attributes as OA;
use App\User\Domain\Collection\Permissions;
use App\User\Application\Validator\PermissionExist;
use Symfony\Component\Validator\Constraints as Assert;

final class Create
{
    #[Assert\NotBlank]
    public string $name;

    #[PermissionExist]
    #[OA\Property(type: 'array', example: Permissions::AVAILABLE, items: new OA\Items(type: 'string'))]
    public array $permissions;
}
