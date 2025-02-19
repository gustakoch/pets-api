<?php

declare(strict_types=1);

namespace App\User\Application\Command\User;

use App\User\Domain\ValueObject\Password;
use App\Shared\Application\Validator\EmailExist;
use Symfony\Component\Validator\Constraints as Assert;

final class Register
{
    #[Assert\NotBlank]
    public string $firstname;

    #[Assert\NotBlank]
    public string $lastname;

    #[Assert\Email]
    #[EmailExist()]
    public string $email;

    #[Assert\NotBlank]
    public Password $password;

    public function __construct(
        string $password,
    ) {
        $this->password = Password::encode($password);
    }
}
