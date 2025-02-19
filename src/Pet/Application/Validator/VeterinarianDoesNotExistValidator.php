<?php

declare(strict_types=1);

namespace App\Pet\Application\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Veterinarian\Domain\Repository\VeterinarianRepository;

final class VeterinarianDoesNotExistValidator extends ConstraintValidator
{
    public function __construct(
        private readonly VeterinarianRepository $repository,
    ) {
    }

    public function validate($value, Constraint|VeterinarianDoesNotExist $constraint): void
    {
        if (!$constraint instanceof VeterinarianDoesNotExist) {
            return;
        }

        if (null === $this->repository->findOneByIdOrNull($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value->publicId())
                ->addViolation();
        }
    }
}
