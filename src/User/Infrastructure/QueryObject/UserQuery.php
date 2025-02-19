<?php

declare(strict_types=1);

namespace App\User\Infrastructure\QueryObject;

use App\User\Domain\User;
use App\User\Domain\ValueObject\UserId;
use Doctrine\ORM\EntityManagerInterface;
use App\User\Infrastructure\Http\View\UserView;
use App\Shared\Infrastructure\QueryHandler\QueryObject;

final class UserQuery implements QueryObject
{
    public function __construct(
        private readonly UserId $userId,
    ) {
    }

    public function execute(EntityManagerInterface $em): ?UserView
    {
        return $em->createQueryBuilder()
            ->select(\sprintf('
                new %s (
                    u.id.publicId,
                    u.firstname,
                    u.lastname,
                    u.email,
                    u.status,
                    r.name,
                    r.permissions,
                    u.createdAt
                )
            ', UserView::class))
            ->from(User::class, 'u')
            ->leftJoin('u.role', 'r')
            ->andWhere('u.id.publicId = :publicId')
            ->setParameter('publicId', $this->userId->publicId())
            ->getQuery()
            ->getOneOrNullResult();
    }
}
