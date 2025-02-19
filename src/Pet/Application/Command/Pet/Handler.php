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
        private readonly PetService $petService,
        private readonly Validator $validator,
    ) {
    }

    public function create(Create $create): Pet
    {
        $this->validator->validate($create);
        $pet = $this->petService->create($create);
        $this->repository->save($pet);

        return $pet;
    }

    public function update(Update $update): Pet
    {
        $this->validator->validate($update);
        $pet = $this->petService->update($update);
        $this->repository->save($pet);

        return $pet;
    }

    public function delete(PetId $id): void
    {
        $this->repository->remove($id);
    }
}
