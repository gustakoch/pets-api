<?php

declare(strict_types=1);

namespace App\Pet\Application\Command\Pet;

use App\Pet\Domain\Pet;
use App\Pet\Domain\ValueObject\PetId;
use App\Pet\Domain\Service\PetService;
use App\Pet\Domain\Repository\PetRepository;
use App\Shared\Application\Validator\Validator;

final class Handler
{
    public function __construct(
        private readonly PetRepository $repository,
        private readonly PetService $service,
        private readonly Validator $validator,
    ) {
    }

    public function create(Create $create): Pet
    {
        $this->validator->validate($create);
        $veterinarians = $this->service->validateVeterinarians($create->veterinarianIds);
        $pet = Pet::create(
            $create->name,
            $create->specie,
            $create->birthDate,
            $create->color,
            $create->description,
            $veterinarians,
        );
        $this->repository->save($pet);

        return $pet;
    }

    public function update(Update $update): Pet
    {
        $this->validator->validate($update);
        $pet = $this->repository->findOneByIdOrNull($update->id);
        $veterinarians = $this->service->validateVeterinarians($update->veterinarianIds);
        $pet->update(
            $update->name,
            $update->specie,
            $update->birthDate,
            $update->color,
            $update->description,
            $veterinarians,
        );
        $this->repository->save($pet);

        return $pet;
    }

    public function delete(PetId $id): void
    {
        $this->repository->remove($id);
    }
}
