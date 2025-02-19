<?php

declare(strict_types=1);

namespace App\Pet\Domain\Service;

use App\Pet\Domain\Pet;
use App\Veterinarian\Domain\Veterinarian;
use App\Pet\Application\Command\Pet\Create;
use App\Pet\Application\Command\Pet\Update;
use App\Pet\Domain\Repository\PetRepository;
use App\Veterinarian\Domain\ValueObject\VeterinarianId;
use App\Veterinarian\Domain\Repository\VeterinarianRepository;

final class PetService
{
    public function __construct(
        private readonly PetRepository $petRepository,
        private readonly VeterinarianRepository $veterinarianRepository,
    ) {
    }

    public function create(Create $create): Pet
    {
        return Pet::create(
            $create->name,
            $create->specie,
            $create->birthDate,
            $create->color,
            $create->description,
            $this->validateVeterinarians($create->veterinarianIds)
        );
    }

    public function update(Update $update): Pet
    {
        /** @var Pet $pet */
        $pet = $this->petRepository->findOneByIdOrNull($update->id);
        if (null === $pet) {
            throw new \DomainException('Pet doesn\'t exist');
        }

        return $pet->update(
            $update->name,
            $update->specie,
            $update->birthDate,
            $update->color,
            $update->description,
            $this->validateVeterinarians($update->veterinarianIds)
        );
    }

    private function getVeterinarianById(VeterinarianId $id): Veterinarian
    {
        $veterinarian = $this->veterinarianRepository->findOneByIdOrNull($id);
        if (null === $veterinarian) {
            throw new \DomainException("Veterinarian with ID {$id->publicId()} not found");
        }

        return $veterinarian;
    }

    private function validateVeterinarians(array $veterinarianIds): array
    {
        return array_map(
            fn (VeterinarianId $id): Veterinarian => $this->getVeterinarianById($id),
            $veterinarianIds
        );
    }
}
