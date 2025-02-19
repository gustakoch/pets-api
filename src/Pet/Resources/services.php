<?php

declare(strict_types=1);

use Symfony\Component\Finder\Finder;
use App\Pet\Domain\Service\PetService;
use App\Pet\Domain\Service\VaccinationService;
use App\Pet\Application\Command\Pet\Handler as PetHandler;
use App\Pet\Application\Command\Vaccination\Handler as VaccinationHandler;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\Pet\\', '../*')
        ->exclude([
            '../{Domain,Test,Resources,PetBundle.php}',
            '../Application/Command/',
            '../Infrastructure/{QueryObject,DependencyInjection}',
            '../Infrastructure/Http/{Controller,View,ArgumentResolver}',
        ]);

    $services->load('App\\Pet\\Infrastructure\\Http\\Controller\\', '../Infrastructure/Http/Controller/')
        ->tag('controller.service_arguments');

    $services->set(PetHandler::class);
    $services->set(VaccinationHandler::class);

    $services->set(PetService::class);
    $services->set(VaccinationService::class);

    $finder = new Finder();
    $finder->files()->in(__DIR__.'/../Infrastructure/Http/ArgumentResolver');
    foreach ($finder as $file) {
        $namespace = 'App\\Pet\\Infrastructure\\Http\\ArgumentResolver\\'.mb_substr($file->getRelativePathname(), 0, -4);
        $services->set($namespace, $namespace)
            ->tag('controller.argument_value_resolver', ['priority' => 150]);
    }
};
