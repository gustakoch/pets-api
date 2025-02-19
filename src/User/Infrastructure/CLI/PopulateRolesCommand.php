<?php

declare(strict_types=1);

namespace App\User\Infrastructure\CLI;

use App\User\Domain\Role;
use App\User\Domain\Collection\Permissions;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\User\Infrastructure\Persistence\RoleRepository;

#[AsCommand(name: 'app:populate-roles', description: 'Populate roles')]
class PopulateRolesCommand extends Command
{
    public function __construct(
        private readonly RoleRepository $repository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $roles = [
            Role::ADMIN => Permissions::AVAILABLE,
            Role::USER => array_diff(Permissions::AVAILABLE, ['CAN_MANAGE_USER']),
            Role::GUEST => array_diff(Permissions::AVAILABLE, ['CAN_MANAGE_USER']),
        ];
        foreach ($roles as $roleName => $permissions) {
            $existingRole = $this->repository->findOneByOrNull(['name' => $roleName]);
            if ($existingRole) {
                $output->writeln("Role <info>$roleName</info> already exists");
                continue;
            }
            $role = Role::create($roleName, new Permissions($permissions));
            $this->repository->save($role);
            $output->writeln("Role <info>$roleName</info> created.");
        }
        $this->repository->flush();

        return Command::SUCCESS;
    }
}
