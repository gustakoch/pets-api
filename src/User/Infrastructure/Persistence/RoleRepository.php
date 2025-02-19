<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Persistence;

use App\User\Domain\Role;
use Doctrine\ORM\EntityManagerInterface;
use App\Shared\Infrastructure\Http\Persistence\DoctrineRepository;
use App\User\Domain\Repository\RoleRepository as RoleRepositoryInterface;

/**
 * @extends DoctrineRepository<Role>
 *
 * @implements RoleRepositoryInterface
 */
final class RoleRepository extends DoctrineRepository implements RoleRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
        parent::__construct($em, Role::class);
    }
}
