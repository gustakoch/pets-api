<?php

declare(strict_types=1);

namespace App\Pet\Domain\Service;

use App\Pet\Domain\Vaccination;
use App\Pet\Domain\Repository\PetRepository;
use App\Pet\Application\Command\Vaccination\Create;
use App\Pet\Application\Command\Vaccination\Update;
use App\Pet\Domain\Repository\VaccinationRepository;
use App\Veterinarian\Domain\Repository\VeterinarianRepository;

final class VaccinationService
{
    public function __construct(
        private readonly VaccinationRepository $vaccinationRepository,
        private readonly VeterinarianRepository $veterinarianRepository,
        private readonly PetRepository $petRepository,
    ) {
    }

    public function create(Create $create): Vaccination
    {
        $pet = $this->petRepository->findOneByIdOrNull($create->petId);
        $veterinarian = $this->veterinarianRepository->findOneByIdOrNull($create->veterinarianId);

        return Vaccination::create(
            $create->name,
            $pet,
            $veterinarian,
            $create->description,
            $create->type,
            $create->applicationDate,
            $create->boosterDate,
            $create->price,
            $create->manufacturer,
            $create->notes
        );
    }

    public function update(Update $update): Vaccination
    {
        /** @var Vaccination $vaccination */
        $vaccination = $this->vaccinationRepository->findOneByIdOrNull($update->id);
        $pet = $this->petRepository->findOneByIdOrNull($update->petId);
        $veterinarian = $this->veterinarianRepository->findOneByIdOrNull($update->veterinarianId);

        return $vaccination->update(
            $update->name,
            $pet,
            $veterinarian,
            $update->description,
            $update->type,
            $update->applicationDate,
            $update->boosterDate,
            $update->price,
            $update->manufacturer,
            $update->notes
        );
    }
}
