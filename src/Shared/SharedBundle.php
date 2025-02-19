<?php

declare(strict_types=1);

namespace App\Shared;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use App\Shared\Infrastructure\DependencyInjection\SharedExtension;

final class SharedBundle extends Bundle
{
    public function getContainerExtension(): SharedExtension
    {
        return new SharedExtension();
    }
}
