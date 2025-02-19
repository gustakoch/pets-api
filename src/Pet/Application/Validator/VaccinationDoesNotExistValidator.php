<?php

declare(strict_types=1);

namespace App\Pet\Application\Validator;

use Symfony\Component\Validator\Constraint;
use App\Pet\Domain\Repository\VaccinationRepository;
use Symfony\Component\Validator\ConstraintValidator;

final class VaccinationDoesNotExistValidator extends ConstraintValidator
{
    public function __construct(
        private readonly VaccinationRepository $repository,
    ) {
    }

    public function validate($value, Constraint|VaccinationDoesNotExist $constraint): void
    {
        if (!$constraint instanceof VaccinationDoesNotExist) {
            return;
        }

        if (null === $this->repository->findOneByIdOrNull($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value->publicId())
                ->addViolation();
        }
    }
}
