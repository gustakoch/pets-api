<?php

declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\User\Domain\User;
use App\Shared\Domain\Repository\RepositoryInterface;

interface UserRepository extends RepositoryInterface
{
    public function findByEmailOrNull(string $email): ?User;
}
