<?php

declare(strict_types=1);

namespace App\Veterinarian\Infrastructure\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use App\Veterinarian\Infrastructure\Persistence\VeterinarianRepository;
use App\Veterinarian\Domain\Repository\VeterinarianRepository as VeterinarianRepositoryInterface;

final class VeterinarianExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $this->addAliases($container);

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../../Resources/'));
        $loader->load('services.php');
    }

    private function addAliases(ContainerBuilder $container): void
    {
        $container->addAliases([
            VeterinarianRepositoryInterface::class => VeterinarianRepository::class,
        ]);
    }
}
