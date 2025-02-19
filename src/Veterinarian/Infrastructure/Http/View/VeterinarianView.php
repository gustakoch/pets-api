<?php

declare(strict_types=1);

namespace App\Veterinarian\Infrastructure\Http\View;

use App\Shared\Infrastructure\Http\View\View;

final class VeterinarianView implements View, \JsonSerializable
{
    public AddressView $address;

    public function __construct(
        public string $publicId,
        public string $name,
        public ?string $email,
        public ?string $phone,
        public bool $hasPhoneWhatsapp,
        public ?string $specializations,
        private string $postalCode,
        private string $street,
        private int $number,
        private string $neighborhood,
        private string $city,
        private string $state,
        private ?string $complement = null,
    ) {
        $this->address = new AddressView(
            $postalCode,
            $street,
            $number,
            $neighborhood,
            $city,
            $state,
            $complement
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'publicId' => $this->publicId,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'hasPhoneWhatsapp' => $this->hasPhoneWhatsapp,
            'specializations' => $this->specializations,
            'address' => $this->address,
        ];
    }
}
