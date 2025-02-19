<?php

declare(strict_types=1);

namespace App\User\Infrastructure\QueryObject;

use App\User\Domain\Role;
use Doctrine\ORM\EntityManagerInterface;
use App\User\Infrastructure\Http\View\RoleView;
use App\User\Infrastructure\Filters\RolesFilter;
use App\Shared\Infrastructure\QueryHandler\QueryObject;

final class RolesQuery implements QueryObject
{
    public function __construct(
        private readonly RolesFilter $filters,
    ) {
    }

    public function execute(EntityManagerInterface $em): array
    {
        $queryBuilder = $em->createQueryBuilder()
            ->select(\sprintf('
                new %s (
                    r.id.publicId,
                    r.name,
                    r.permissions,
                    r.createdAt
                )
            ', RoleView::class))
            ->from(Role::class, 'r');

        $this->filters->apply($queryBuilder);

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }
}
