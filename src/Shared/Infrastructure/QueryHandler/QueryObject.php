<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\QueryHandler;

use Doctrine\ORM\EntityManagerInterface;
use App\Shared\Infrastructure\Http\View\View;

interface QueryObject
{
    public function execute(EntityManagerInterface $em): string|View|array|null;
}
