<?php

declare(strict_types=1);

namespace App\Pet\Domain\Service;

use App\Veterinarian\Domain\Veterinarian;
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

    public function validateVeterinarians(array $veterinarianIds): array
    {
        return array_map(
            fn (VeterinarianId $id): Veterinarian => $this->getVeterinarianById($id),
            $veterinarianIds
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
}
