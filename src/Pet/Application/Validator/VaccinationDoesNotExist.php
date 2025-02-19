<?php

declare(strict_types=1);

namespace App\Pet\Application\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class VaccinationDoesNotExist extends Constraint
{
    public string $message = 'The vaccination with ID "{{ value }}" does not exist';

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
