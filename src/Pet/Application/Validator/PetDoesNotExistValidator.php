<?php

declare(strict_types=1);

namespace App\Pet\Application\Validator;

use Symfony\Component\Validator\Constraint;
use App\Pet\Domain\Repository\PetRepository;
use Symfony\Component\Validator\ConstraintValidator;

final class PetDoesNotExistValidator extends ConstraintValidator
{
    public function __construct(
        private readonly PetRepository $repository,
    ) {
    }

    public function validate($value, Constraint|PetDoesNotExist $constraint): void
    {
        if (!$constraint instanceof PetDoesNotExist) {
            return;
        }

        if (null === $this->repository->findOneByIdOrNull($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value->publicId())
                ->addViolation();
        }
    }
}
