<?php

declare(strict_types=1);

namespace App\User\Application\Command\User;

use App\User\Domain\Role;
use App\User\Domain\User;
use App\User\Domain\ValueObject\UserId;
use App\User\Domain\Repository\RoleRepository;
use App\User\Domain\Repository\UserRepository;
use App\Shared\Application\Validator\Validator;
use App\User\Domain\Message\UserRegistered;
use Symfony\Component\Messenger\MessageBusInterface;

final class Handler
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly RoleRepository $roleRepository,
        private readonly MessageBusInterface $messageBus,
        private readonly Validator $validator,
    ) {
    }

    public function register(Register $register): User
    {
        $this->validator->validate($register);
        $role = $this->roleRepository->findOneByOrNull(['name' => Role::GUEST]);
        if (null === $role) {
            throw new \DomainException('Role not found');
        }
        $user = User::register(
            $register->firstname,
            $register->lastname,
            $register->email,
            $register->password,
            $role,
        );
        $this->userRepository->save($user);
        $this->messageBus->dispatch(new UserRegistered($user));

        return $user;
    }

    public function update(Update $update): User
    {
        $this->validator->validate($update);
        /** @var User $user */
        $user = $this->userRepository->findOneByIdOrNull($update->id);
        if (null === $user) {
            throw new \DomainException('User not found');
        }
        $user->updateFirstname($update->firstname)
            ->updateLastname($update->lastname)
            ->updateRole($this->roleRepository->findOneByIdOrNull($update->roleId));
        $this->userRepository->save($user);

        return $user;
    }

    public function delete(UserId $id): void
    {
        $this->userRepository->remove($id);
    }
}
