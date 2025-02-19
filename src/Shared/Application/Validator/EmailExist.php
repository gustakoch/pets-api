<?php

declare(strict_types=1);

namespace App\Shared\Application\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class EmailExist extends Constraint
{
    public string $message = 'Email already exists';

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
