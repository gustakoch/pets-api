<?php

declare(strict_types=1);

namespace App\User;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use App\User\Infrastructure\DependencyInjection\UserExtension;

final class UserBundle extends Bundle
{
    public function getContainerExtension(): UserExtension
    {
        return new UserExtension();
    }
}
