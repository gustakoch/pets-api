<?php

declare(strict_types=1);

namespace App\Veterinarian\Domain\ValueObject;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class Address
{
    public function __construct(
        #[ORM\Column(length: 20, type: Types::STRING)]
        private string $postalCode,
        #[ORM\Column(length: 255, type: Types::STRING)]
        private string $street,
        #[ORM\Column(length: 50, type: Types::INTEGER)]
        private int $number,
        #[ORM\Column(length: 255, type: Types::STRING)]
        private string $neighborhood,
        #[ORM\Column(length: 255, type: Types::STRING)]
        private string $city,
        #[ORM\Column(length: 255, type: Types::STRING)]
        private string $state,
        #[ORM\Column(length: 255, type: Types::STRING, nullable: true)]
        private ?string $complement = null,
    ) {
    }
}
