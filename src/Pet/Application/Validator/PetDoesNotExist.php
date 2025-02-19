<?php

declare(strict_types=1);

namespace App\Pet\Application\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class PetDoesNotExist extends Constraint
{
    public string $message = 'The pet with ID "{{ value }}" does not exist';

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
