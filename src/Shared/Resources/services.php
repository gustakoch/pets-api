<?php

declare(strict_types=1);

use Symfony\Component\Finder\Finder;
use App\Shared\Infrastructure\Http\Response\Response;
use App\Shared\Infrastructure\Exception\ExceptionListener;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\Shared\\', '../*')
        ->exclude([
            '../{Domain,Test,Resources,SharedBundle.php}',
            '../Application/Command/',
            '../Infrastructure/{QueryObject,DependencyInjection}',
            '../Infrastructure/Http/{Controller,View,ArgumentResolver}',
        ]);

    $services->set(Response::class);
    $services->set(ExceptionListener::class)
        ->tag('kernel.event_listener', [
            'event' => 'kernel.exception',
        ]);

    $finder = new Finder();
    $finder->files()->in(__DIR__.'/../Infrastructure/Http/ArgumentResolver');
    foreach ($finder as $file) {
        $namespace = 'App\\Shared\\Infrastructure\\Http\\ArgumentResolver\\'.mb_substr($file->getRelativePathname(), 0, -4);
        $services->set($namespace, $namespace)
            ->tag('controller.argument_value_resolver', ['priority' => 150]);
    }
};
