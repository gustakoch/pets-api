<?php

declare(strict_types=1);

namespace App\User\Application\Validator;

use App\User\Domain\Collection\Permissions;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class PermissionExistValidator extends ConstraintValidator
{
    public function validate($values, Constraint|PermissionExist $constraint)
    {
        if (!$constraint instanceof PermissionExist) {
            return;
        }

        if (!empty(array_diff($values, Permissions::AVAILABLE))) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
