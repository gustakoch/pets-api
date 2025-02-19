<?php

declare(strict_types=1);

namespace App\User\Test\DataFixtures;

use App\User\Domain\Role;
use App\User\Domain\User;
use Doctrine\Persistence\ObjectManager;
use App\User\Domain\ValueObject\Password;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserTestFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $role = $this->getReference(RolesFixture::ROLE_USER_REFERENCE, Role::class);
        $user = User::register(
            firstname: 'Test',
            lastname: 'Test',
            email: 'test@test.com',
            password: Password::encode('secretPass1'),
            role: $role,
        );
        $manager->persist($user);
        $manager->flush();
    }
}
