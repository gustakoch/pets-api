<?php

declare(strict_types=1);

namespace App\Veterinarian\Application\Command\Veterinarian;

use App\Pet\Application\Validator\VeterinarianDoesNotExist;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Validator\Constraints as Assert;
use App\Veterinarian\Domain\ValueObject\VeterinarianId;

final class Update
{
    #[Assert\NotBlank]
    public string $name;

    #[Assert\Email]
    public ?string $email;

    public ?string $phone;

    #[Assert\NotNull]
    public bool $hasPhoneWhatsapp;

    public ?string $specializations;

    public Address $address;

    public function __construct(
        #[Assert\NotBlank]
        #[VeterinarianDoesNotExist]
        #[Ignore]
        public VeterinarianId $id,
    ) {
    }
}
