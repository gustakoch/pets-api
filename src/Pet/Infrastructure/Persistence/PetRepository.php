<?php

declare(strict_types=1);

namespace App\Pet\Infrastructure\Persistence;

use App\Pet\Domain\Pet;
use Doctrine\ORM\EntityManagerInterface;
use App\Shared\Infrastructure\Http\Persistence\DoctrineRepository;
use App\Pet\Domain\Repository\PetRepository as PetRepositoryInterface;

final class PetRepository extends DoctrineRepository implements PetRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
        parent::__construct($em, Pet::class);
    }
}
