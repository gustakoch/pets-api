<?php

declare(strict_types=1);

namespace App\Pet\Infrastructure\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use App\Pet\Infrastructure\Persistence\PetRepository;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use App\Pet\Infrastructure\Persistence\VaccinationRepository;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use App\Pet\Domain\Repository\PetRepository as PetRepositoryInterface;
use App\Veterinarian\Infrastructure\Persistence\VeterinarianRepository;
use App\Pet\Domain\Repository\VaccinationRepository as VaccinationRepositoryInterface;
use App\Veterinarian\Domain\Repository\VeterinarianRepository as VeterinarianRepositoryInterface;

final class PetExtension extends Extension
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
            PetRepositoryInterface::class => PetRepository::class,
            VeterinarianRepositoryInterface::class => VeterinarianRepository::class,
            VaccinationRepositoryInterface::class => VaccinationRepository::class,
        ]);
    }
}
