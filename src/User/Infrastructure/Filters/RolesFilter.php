<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Filters;

use Doctrine\ORM\QueryBuilder;

final class RolesFilter
{
    public function __construct(
        private ?string $name,
    ) {
    }

    public function apply(QueryBuilder $queryBuilder): void
    {
        $this->filterByName($queryBuilder);
    }

    private function filterByName(QueryBuilder $queryBuilder): void
    {
        if (null !== $this->name) {
            $queryBuilder
                ->andWhere('LOWER(r.name) LIKE :name')
                ->setParameter('name', '%'.mb_strtolower($this->name).'%');
        }
    }
}
