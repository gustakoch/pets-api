<?php

declare(strict_types=1);

namespace App\Notification\Resources;

use App\Notification\Infrastructure\MessageHandler\SendEmailHandler;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()
        ->defaults()
        // ->bind('$frontendUrl', '%env(FRONTEND_URL)%')
        ->autowire()
        ->autoconfigure();

    $services->load('App\\Notification\\', '../*')
        ->exclude([
            '../{Resources,NotificationBundle.php}',
            '../Infrastructure/DependencyInjection',
        ]);

    $services->set(SendEmailHandler::class)->args([
        service('mailer'),
        service('twig'),
    ]);
};
