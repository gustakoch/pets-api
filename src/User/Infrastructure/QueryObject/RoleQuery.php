<?php

declare(strict_types=1);

namespace App\User\Infrastructure\QueryObject;

use App\User\Domain\Role;
use App\User\Domain\ValueObject\RoleId;
use Doctrine\ORM\EntityManagerInterface;
use App\User\Infrastructure\Http\View\RoleView;
use App\Shared\Infrastructure\QueryHandler\QueryObject;

final class RoleQuery implements QueryObject
{
    public function __construct(
        private readonly RoleId $roleId,
    ) {
    }

    public function execute(EntityManagerInterface $em): ?RoleView
    {
        return $em->createQueryBuilder()
            ->select(\sprintf('
                new %s (
                    r.id.publicId,
                    r.name,
                    r.permissions,
                    r.createdAt
                )
            ', RoleView::class))
            ->from(Role::class, 'r')
            ->andWhere('r.id.publicId = :publicId')
            ->setParameter('publicId', $this->roleId->publicId())
            ->getQuery()
            ->getOneOrNullResult();
    }
}
