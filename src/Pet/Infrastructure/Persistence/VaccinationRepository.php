<?php

declare(strict_types=1);

namespace App\Pet\Infrastructure\Persistence;

use App\Pet\Domain\Vaccination;
use Doctrine\ORM\EntityManagerInterface;
use App\Shared\Infrastructure\Http\Persistence\DoctrineRepository;
use App\Pet\Domain\Repository\VaccinationRepository as VaccinationRepositoryInterface;

final class VaccinationRepository extends DoctrineRepository implements VaccinationRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
        parent::__construct($em, Vaccination::class);
    }
}
