<?php

declare(strict_types=1);

namespace App\Veterinarian\Application\Command\Veterinarian;

use Symfony\Component\Validator\Constraints as Assert;

final class Address
{
    #[Assert\NotBlank]
    public string $postalCode;

    #[Assert\NotBlank]
    public ?string $street;

    #[Assert\NotBlank]
    public int $number;

    public ?string $complement;

    #[Assert\NotBlank]
    public string $neighborhood;

    #[Assert\NotBlank]
    public string $city;

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 2)]
    public string $state;
}
