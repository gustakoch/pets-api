<?php

declare(strict_types=1);

namespace App\Shared\Domain\Repository;

use App\Shared\Domain\ValueObject\BaseId;

/**
 * @template T
 */
interface RepositoryInterface
{
    /**
     * @param T $entity
     */
    public function save(mixed $entity): void;

    public function flush(): void;

    public function remove(BaseId $id): void;

    /**
     * @return ?T
     */
    public function findOneByOrNull(array $criteria, ?array $orderBy = null);

    /**
     * @return ?T
     */
    public function findOneByIdOrNull(BaseId $id);

    /**
     * @return array<T>
     */
    public function findAll(): array;
}
