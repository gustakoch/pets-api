<?php

declare(strict_types=1);

namespace App\Notification;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use App\Notification\Infrastructure\DependencyInjection\NotificationExtension;

final class NotificationBundle extends Bundle
{
    public function getContainerExtension(): NotificationExtension
    {
        return new NotificationExtension();
    }
}
