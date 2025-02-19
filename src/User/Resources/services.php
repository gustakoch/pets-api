<?php

declare(strict_types=1);

use Symfony\Component\Finder\Finder;
use App\User\Domain\Repository\UserRepository;
use App\Shared\Application\Validator\EmailExistValidator;
use App\User\Infrastructure\Security\AuthenticationListener;
use App\User\Application\Command\Role\Handler as RoleHandler;
use App\User\Application\Command\User\Handler as UserHandler;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\User\\', '../*')
        ->exclude([
            '../{Domain,Test,Resources,UserBundle.php}',
            '../Application/Command/',
            '../Infrastructure/{QueryObject,DependencyInjection}',
            '../Infrastructure/Http/{Controller,View,ArgumentResolver}',
        ]);

    $services->load('App\\User\\Infrastructure\\Http\\Controller\\', '../Infrastructure/Http/Controller/')
        ->tag('controller.service_arguments');

    $services->set(UserHandler::class);
    $services->set(RoleHandler::class);

    $services->set(EmailExistValidator::class)
        ->arg('$repository', service(UserRepository::class))
        ->tag('validator.constraint_validator');

    $services->set(AuthenticationListener::class)
        ->tag('kernel.event_listener', [
            'event' => 'lexik_jwt_authentication.on_authentication_success',
            'method' => 'onAuthenticationSuccess',
        ])
        ->tag('kernel.event_listener', [
            'event' => 'lexik_jwt_authentication.on_authentication_failure',
            'method' => 'onAuthenticationFailure',
        ])
        ->tag('kernel.event_listener', [
            'event' => 'lexik_jwt_authentication.on_jwt_invalid',
            'method' => 'onJWTInvalid',
        ])
        ->tag('kernel.event_listener', [
            'event' => 'lexik_jwt_authentication.on_jwt_expired',
            'method' => 'onJWTExpired',
        ]);

    $finder = new Finder();
    $finder->files()->in(__DIR__.'/../Infrastructure/Http/ArgumentResolver');
    foreach ($finder as $file) {
        $namespace = 'App\\User\\Infrastructure\\Http\\ArgumentResolver\\'.mb_substr($file->getRelativePathname(), 0, -4);
        $services->set($namespace, $namespace)
            ->tag('controller.argument_value_resolver', ['priority' => 150]);
    }
};
