<?php

declare(strict_types=1);

namespace App\User\Infrastructure\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use App\User\Infrastructure\Persistence\RoleRepository;
use App\User\Infrastructure\Persistence\UserRepository;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use App\User\Domain\Repository\RoleRepository as RoleRepositoryInterface;
use App\User\Domain\Repository\UserRepository as UserRepositoryInterface;

final class UserExtension extends Extension
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
            UserRepositoryInterface::class => UserRepository::class,
            RoleRepositoryInterface::class => RoleRepository::class,
        ]);
    }
}
