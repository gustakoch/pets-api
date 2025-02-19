<?php

declare(strict_types=1);

namespace App\User\Application\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class PermissionExist extends Constraint
{
    public string $message = 'Unsupported permission';

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
