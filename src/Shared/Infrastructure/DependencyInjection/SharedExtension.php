<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

final class SharedExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $this->addAliases($container);

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../../Resources'));
        $loader->load('services.php');
    }

    private function addAliases(ContainerBuilder $container): void
    {
        $container->addAliases([
        ]);
    }
}
