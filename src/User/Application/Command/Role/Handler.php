<?php

declare(strict_types=1);

namespace App\User\Application\Command\Role;

use App\User\Domain\Role;
use App\User\Domain\Collection\Permissions;
use App\User\Domain\ValueObject\Permission;
use App\User\Domain\Repository\RoleRepository;
use App\Shared\Application\Validator\Validator;

final class Handler
{
    public function __construct(
        private readonly RoleRepository $repository,
        private readonly Validator $validator,
    ) {
    }

    public function create(Create $create): Role
    {
        $this->validator->validate($create);
        $permissions = new Permissions();
        foreach ($create->permissions as $permission) {
            $permissions->add(new Permission($permission));
        }
        $role = Role::create($create->name, $permissions);
        $this->repository->save($role);

        return $role;
    }

    public function update(Update $update): Role
    {
        $this->validator->validate($update);
        /** @var Role $role */
        $role = $this->repository->findOneByIdOrNull($update->id);
        if (null === $role) {
            throw new \DomainException('Role not found');
        }
        $permissions = new Permissions();
        foreach ($update->permissions as $permission) {
            $permissions->add(new Permission($permission));
        }
        $role->updateName($update->name);
        $role->updatePermissions($permissions);
        $this->repository->save($role);

        return $role;
    }
}
