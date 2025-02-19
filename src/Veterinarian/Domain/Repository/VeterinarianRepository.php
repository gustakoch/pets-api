<?php

declare(strict_types=1);

namespace App\Veterinarian\Domain\Repository;

use App\Veterinarian\Domain\Veterinarian;
use App\Shared\Domain\Repository\RepositoryInterface;

interface VeterinarianRepository extends RepositoryInterface
{
    public function findByEmailOrNull(string $email): ?Veterinarian;
}
