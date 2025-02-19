<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Security;

use App\User\Domain\User;
use App\User\Domain\ValueObject\UserStatus;
use App\User\Domain\Repository\UserRepository;
use App\User\Domain\Exception\UserInactiveException;
use Symfony\Component\Security\Core\User\UserInterface;
use App\User\Domain\Exception\UserPasswordExpiredException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

final class UserProvider implements UserProviderInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    public function loadUserByIdentifier(string $email): User
    {
        if ($user = $this->userRepository->findByEmailOrNull($email)) {
            if ($user->isPasswordExpired()) {
                $user->updatedStatus(UserStatus::Expired);
                $this->userRepository->save($user);
                $this->userRepository->flush();
            }
            if ($user->status()->isInactive()) {
                throw new UserInactiveException();
            }
            if ($user->status()->isExpired()) {
                throw new UserPasswordExpiredException();
            }

            return $user;
        }

        throw new UserNotFoundException(\sprintf('Email "%s" not found', $email));
    }

    public function refreshUser(UserInterface $user): User
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(\sprintf('Instances of "%s" are not supported.', $user::class));
        }

        return $this->loadUserByIdentifier($user->email());
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }
}
