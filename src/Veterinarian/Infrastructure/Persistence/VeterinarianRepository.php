<?php

declare(strict_types=1);

namespace App\Veterinarian\Infrastructure\Persistence;

use Doctrine\ORM\EntityManagerInterface;
use App\Veterinarian\Domain\Veterinarian;
use App\Shared\Infrastructure\Http\Persistence\DoctrineRepository;
use App\Veterinarian\Domain\Repository\VeterinarianRepository as VeterinarianRepositoryInterface;

/**
 * @extends DoctrineRepository<Veterinarian>
 *
 * @implements VeterinarianRepositoryInterface
 */
final class VeterinarianRepository extends DoctrineRepository implements VeterinarianRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
        parent::__construct($em, Veterinarian::class);
    }

    public function findByEmailOrNull(string $email): ?Veterinarian
    {
        return $this->em->getRepository(Veterinarian::class)->findOneBy(['email' => $email]);
    }
}
