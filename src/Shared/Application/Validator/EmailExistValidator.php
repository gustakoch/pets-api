<?php

declare(strict_types=1);

namespace App\Shared\Application\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class EmailExistValidator extends ConstraintValidator
{
    public function __construct(
        private readonly object $repository,
    ) {
    }

    public function validate($value, Constraint|EmailExist $constraint): void
    {
        if (!$constraint instanceof EmailExist) {
            return;
        }

        if (method_exists($this->repository, 'findByEmailOrNull') && null !== $this->repository->findByEmailOrNull($value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
