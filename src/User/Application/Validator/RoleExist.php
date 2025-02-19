<?php

declare(strict_types=1);

namespace App\User\Application\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class RoleExist extends Constraint
{
    public string $message = 'Role does not exist';

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
