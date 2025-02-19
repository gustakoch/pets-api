<?php

declare(strict_types=1);

use Symfony\Component\Finder\Finder;
use App\Shared\Application\Validator\EmailExistValidator;
use App\Veterinarian\Application\Command\Veterinarian\Handler;
use App\Veterinarian\Domain\Repository\VeterinarianRepository;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\Veterinarian\\', '../*')
        ->exclude([
            '../{Domain,Test,Resources,VeterinarianBundle.php}',
            '../Application/Command/',
            '../Infrastructure/{QueryObject,DependencyInjection}',
            '../Infrastructure/Http/{Controller,View,ArgumentResolver}',
        ]);

    $services->load('App\\Veterinarian\\Infrastructure\\Http\\Controller\\', '../Infrastructure/Http/Controller/')
        ->tag('controller.service_arguments');

    $services->set(Handler::class);

    $services->set(EmailExistValidator::class)
        ->arg('$repository', service(VeterinarianRepository::class))
        ->tag('validator.constraint_validator');

    $finder = new Finder();
    $finder->files()->in(__DIR__.'/../Infrastructure/Http/ArgumentResolver');
    foreach ($finder as $file) {
        $namespace = 'App\\Veterinarian\\Infrastructure\\Http\\ArgumentResolver\\'.mb_substr($file->getRelativePathname(), 0, -4);
        $services->set($namespace, $namespace)
            ->tag('controller.argument_value_resolver', ['priority' => 150]);
    }
};
