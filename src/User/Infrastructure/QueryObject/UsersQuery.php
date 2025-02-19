<?php

declare(strict_types=1);

namespace App\User\Infrastructure\QueryObject;

use App\User\Domain\User;
use Doctrine\ORM\EntityManagerInterface;
use App\User\Infrastructure\Http\View\UserView;
use App\Shared\Infrastructure\QueryHandler\QueryObject;

final class UsersQuery implements QueryObject
{
    public function execute(EntityManagerInterface $em): array
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
            ->getQuery()
            ->getResult();
    }
}
