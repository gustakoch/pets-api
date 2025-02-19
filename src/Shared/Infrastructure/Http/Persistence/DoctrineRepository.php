<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Http\Persistence;

use Doctrine\ORM\EntityManagerInterface;
use App\Shared\Domain\ValueObject\BaseId;
use App\Shared\Domain\Repository\RepositoryInterface;

/**
 * @template T
 *
 * @implements RepositoryInterface<T>
 */
abstract class DoctrineRepository implements RepositoryInterface
{
    /**
     * @param class-string<T> $entityClass
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly string $entityClass,
    ) {
    }

    /**
     * @param T $entity
     */
    public function save(mixed $entity): void
    {
        $this->entityManager->persist($entity);
    }

    public function remove(BaseId $id): void
    {
        $entity = $this->findOneByIdOrNull($id);
        if (null !== $entity) {
            $this->entityManager->remove($entity);
        }
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    /**
     * @return T|null
     */
    public function findOneByOrNull(array $criteria, ?array $orderBy = null)
    {
        return $this->entityManager->getRepository($this->entityClass)->findOneBy($criteria, $orderBy);
    }

    /**
     * @return T|null
     */
    public function findOneByIdOrNull(BaseId $id, ?array $orderBy = null)
    {
        return $this->findOneByOrNull(['id.publicId' => $id->publicId()], $orderBy);
    }

    /**
     * @return array<T>
     */
    public function findAll(): array
    {
        return $this->entityManager->getRepository($this->entityClass)->findAll();
    }
}
