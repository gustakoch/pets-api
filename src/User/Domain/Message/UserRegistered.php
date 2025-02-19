<?php

declare(strict_types=1);

namespace App\User\Domain\Message;

use App\User\Domain\User;

class UserRegistered
{
    public function __construct(
        private readonly User $user,
    ) {
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
