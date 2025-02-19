<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Persistence;

use App\User\Domain\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Shared\Infrastructure\Http\Persistence\DoctrineRepository;
use App\User\Domain\Repository\UserRepository as UserRepositoryInterface;

/**
 * @extends DoctrineRepository<User>
 *
 * @implements UserRepositoryInterface
 */
final class UserRepository extends DoctrineRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
        parent::__construct($em, User::class);
    }

    public function findByEmailOrNull(string $email): ?User
    {
        return $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
    }
}
