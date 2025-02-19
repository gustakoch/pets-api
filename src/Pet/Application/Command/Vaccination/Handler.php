<?php

declare(strict_types=1);

namespace App\Pet\Application\Command\Vaccination;

use App\Pet\Domain\Vaccination;
use App\Pet\Domain\ValueObject\VaccinationId;
use App\Pet\Domain\Service\VaccinationService;
use App\Shared\Application\Validator\Validator;
use App\Pet\Domain\Repository\VaccinationRepository;

final class Handler
{
    public function __construct(
        private readonly VaccinationRepository $repository,
        private readonly VaccinationService $service,
        private readonly Validator $validator,
    ) {
    }

    public function create(Create $create): Vaccination
    {
        $this->validator->validate($create);
        $vaccination = $this->service->create($create);
        $this->repository->save($vaccination);

        return $vaccination;
    }

    public function update(Update $update): Vaccination
    {
        $this->validator->validate($update);
        $vaccination = $this->service->update($update);
        $this->repository->save($vaccination);

        return $vaccination;
    }

    public function delete(VaccinationId $id): void
    {
        $this->repository->remove($id);
    }
}
