<?php

declare(strict_types=1);

namespace App\Pet\Infrastructure\Filters;

use Doctrine\ORM\QueryBuilder;

final class PetsFilters
{
    public function __construct(
        private ?string $name,
        private ?string $specie,
    ) {
    }

    public function apply(QueryBuilder $queryBuilder): void
    {
        $this->filterByName($queryBuilder);
        $this->filterBySpecie($queryBuilder);
    }

    private function filterByName(QueryBuilder $queryBuilder): void
    {
        if (null !== $this->name) {
            $queryBuilder
                ->andWhere('LOWER(p.name) LIKE :name')
                ->setParameter('name', '%'.mb_strtolower($this->name).'%');
        }
    }

    private function filterBySpecie(QueryBuilder $queryBuilder): void
    {
        if (null !== $this->specie) {
            $queryBuilder
                ->andWhere('LOWER(p.specie) = :specie')
                ->setParameter('specie', mb_strtolower($this->specie));
        }
    }
}
