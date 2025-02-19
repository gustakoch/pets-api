<?php

declare(strict_types=1);

namespace App\Pet\Infrastructure\QueryObject;

use App\Pet\Domain\Pet;
use Doctrine\ORM\EntityManagerInterface;
use App\Pet\Infrastructure\Http\View\PetView;
use App\Pet\Infrastructure\Filters\PetsFilters;
use App\Shared\Infrastructure\QueryHandler\QueryObject;

final class PetsQuery implements QueryObject
{
    public function __construct(
        private readonly PetsFilters $filters,
    ) {
    }

    public function execute(EntityManagerInterface $em): array
    {
        $queryBuilder = $em->createQueryBuilder()
            ->select('p', 'v')
            ->from(Pet::class, 'p')
            ->leftJoin('p.veterinarians', 'v');

        $this->filters->apply($queryBuilder);
        $pets = $queryBuilder->getQuery()->getResult();

        return array_map(
            static fn (Pet $pet) => new PetView(
                id: $pet->getId()->publicId(),
                name: $pet->getName(),
                specie: $pet->getSpecie()->value,
                birthDate: $pet->getBirthDate(),
                color: $pet->getColor(),
                description: $pet->getDescription(),
                veterinarians: $pet->getVeterinarians()
            ),
            $pets
        );
    }
}
