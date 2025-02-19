<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Types\JsonType;
use App\User\Domain\Collection\Permissions;
use App\User\Domain\ValueObject\Permission;
use Doctrine\DBAL\Platforms\AbstractPlatform;

final class PermissionsType extends JsonType
{
    public const NAME = 'permissions';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        $permissions = [];
        if ($value instanceof Permissions) {
            foreach ($value as $permission) {
                $permissions[] = $permission;
            }
            $value = json_encode($permissions, \JSON_THROW_ON_ERROR);
        }
        return $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $permissions = new Permissions();
        foreach (json_decode($value, true, 512, \JSON_THROW_ON_ERROR) as $permission) {
            $permissions->add(new Permission($permission));
        }

        return $permissions;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
