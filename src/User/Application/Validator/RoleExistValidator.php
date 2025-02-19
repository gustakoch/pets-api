<?php

declare(strict_types=1);

namespace App\User\Application\Validator;

use Symfony\Component\Validator\Constraint;
use App\User\Domain\Repository\RoleRepository;
use Symfony\Component\Validator\ConstraintValidator;

final class RoleExistValidator extends ConstraintValidator
{
    public function __construct(
        private readonly RoleRepository $repository,
    ) {
    }

    public function validate($value, Constraint|RoleExist $constraint)
    {
        if (!$constraint instanceof RoleExist) {
            return;
        }
        if (null === $this->repository->findOneByIdOrNull($value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
