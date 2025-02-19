<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\QueryHandler;

use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Shared\Infrastructure\Http\View\View;

final class QueryExecutor
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function execute(QueryObject $queryObject): string|View|array|null
    {
        try {
            return $queryObject->execute($this->em);
        } catch (\Exception $exception) {
            $this->logger->critical($exception->getMessage());
            throw $exception;
        }
    }
}
