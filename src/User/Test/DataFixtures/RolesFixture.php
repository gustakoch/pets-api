<?php

declare(strict_types=1);

namespace App\User\Test\DataFixtures;

use App\User\Domain\Role;
use Doctrine\Persistence\ObjectManager;
use App\User\Domain\Collection\Permissions;
use Doctrine\Bundle\FixturesBundle\Fixture;

class RolesFixture extends Fixture
{
    public const ROLE_ADMIN_REFERENCE = 'role-admin';
    public const ROLE_USER_REFERENCE = 'role-user';
    public const ROLE_GUEST_REFERENCE = 'role-guest';

    public function load(ObjectManager $manager): void
    {
        $roles = [
            Role::ADMIN => Permissions::AVAILABLE,
            Role::USER => array_diff(Permissions::AVAILABLE, ['CAN_MANAGE_USER']),
            Role::GUEST => array_diff(Permissions::AVAILABLE, ['CAN_MANAGE_USER']),
        ];
        foreach ($roles as $roleName => $permissions) {
            $role = Role::create($roleName, new Permissions($permissions));
            $manager->persist($role);
            $this->addReference("role-$roleName", $role);
        }
        $manager->persist($role);
        $manager->flush();
    }
}
