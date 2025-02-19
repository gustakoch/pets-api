<?php

declare(strict_types=1);

namespace App\Shared\Application\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Shared\Application\Exception\EntityValidationException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class Validator
{
    public function __construct(
        private readonly ValidatorInterface $validator,
    ) {
    }

    /**
     * Validate an object and throw exceptions on validation errors.
     *
     * @param object                          $object      Object to validate
     * @param Constraint|array|null           $constraints Specific constraints to apply (optional)
     * @param string|GroupSequence|array|null $groups      Validation groups (optional)
     *
     * @throws EntityValidationException
     */
    public function validate(object $object, Constraint|array|null $constraints = null, string|GroupSequence|array|null $groups = null): void
    {
        $violations = $this->validator->validate($object, $constraints, $groups);
        if ($violations->count() > 0) {
            throw $this->buildValidationException($violations);
        }
    }

    /**
     * Build a custom EntityValidationException from violations.
     */
    private function buildValidationException(ConstraintViolationListInterface $violations): EntityValidationException
    {
        $errors = [];
        foreach ($violations as $violation) {
            $propertyPath = $violation->getPropertyPath();
            $message = $violation->getMessage();
            $errors[$propertyPath][] = $message;
        }

        return new EntityValidationException($errors);
    }
}
