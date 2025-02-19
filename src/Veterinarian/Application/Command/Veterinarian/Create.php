<?php

declare(strict_types=1);

namespace App\Veterinarian\Application\Command\Veterinarian;

use App\Shared\Application\Validator\EmailExist;
use Symfony\Component\Validator\Constraints as Assert;

final class Create
{
    #[Assert\NotBlank]
    public string $name;

    #[Assert\Email]
    #[EmailExist]
    public ?string $email;

    public ?string $phone;

    #[Assert\NotNull]
    public bool $hasPhoneWhatsapp;

    public ?string $specializations;

    public Address $address;
}
