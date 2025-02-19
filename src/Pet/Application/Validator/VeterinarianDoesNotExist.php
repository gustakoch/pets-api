<?php

declare(strict_types=1);

namespace App\Pet\Application\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class VeterinarianDoesNotExist extends Constraint
{
    public string $message = 'The veterinarian with ID "{{ value }}" does not exist';

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
