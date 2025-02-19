<?php

declare(strict_types=1);

namespace App\Pet\Infrastructure\QueryObject;

use App\Pet\Domain\Vaccination;
use Doctrine\ORM\EntityManagerInterface;
use App\Pet\Infrastructure\Http\View\VaccinationView;
use App\Shared\Infrastructure\QueryHandler\QueryObject;

final class VaccinationsQuery implements QueryObject
{
    public function execute(EntityManagerInterface $em): array
    {
        return $em->createQueryBuilder()
            ->select(
                \sprintf('new %s (
                    vac.id.publicId,
                    vac.name,
                    vac.description,
                    pet.id.publicId as petPublicId,
                    pet.name as petName,
                    vet.id.publicId as veterinarianPublicId,
                    vet.name as veterinarianName,
                    vac.type,
                    vac.applicationDate,
                    vac.boosterDate,
                    vac.price,
                    vac.manufacturer,
                    vac.notes
                )', VaccinationView::class)
            )
            ->from(Vaccination::class, 'vac')
            ->leftJoin('vac.pet', 'pet')
            ->leftJoin('vac.veterinarian', 'vet')
            ->getQuery()
            ->getResult();
    }
}
