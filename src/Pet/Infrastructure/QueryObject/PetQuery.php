<?php

declare(strict_types=1);

namespace App\Pet\Infrastructure\QueryObject;

use App\Pet\Domain\Pet;
use App\Pet\Domain\ValueObject\PetId;
use Doctrine\ORM\EntityManagerInterface;
use App\Pet\Infrastructure\Http\View\PetView;
use App\Shared\Infrastructure\QueryHandler\QueryObject;

final class PetQuery implements QueryObject
{
    public function __construct(
        private readonly PetId $petId,
    ) {
    }

    public function execute(EntityManagerInterface $em): ?PetView
    {
        $pet = $em->createQueryBuilder()
            ->select('p', 'v')
            ->from(Pet::class, 'p')
            ->leftJoin('p.veterinarians', 'v')
            ->andWhere('p.id.publicId = :publicId')
            ->setParameter('publicId', $this->petId->publicId())
            ->getQuery()
            ->getOneOrNullResult();

        if (!$pet) {
            return null;
        }

        return new PetView(
            id: $pet->getId()->publicId(),
            name: $pet->getName(),
            specie: $pet->getSpecie()->value,
            birthDate: $pet->getBirthDate(),
            color: $pet->getColor(),
            description: $pet->getDescription(),
            veterinarians: $pet->getVeterinarians()
        );
    }
}
