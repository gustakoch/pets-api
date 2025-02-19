<?php

declare(strict_types=1);

namespace App\Veterinarian\Infrastructure\Http\View;

use App\Shared\Infrastructure\Http\View\View;

final class AddressView implements View
{
    public function __construct(
        public string $postalCode,
        public string $street,
        public int $number,
        public string $neighborhood,
        public string $city,
        public string $state,
        public ?string $complement = null,
    ) {
    }
}
