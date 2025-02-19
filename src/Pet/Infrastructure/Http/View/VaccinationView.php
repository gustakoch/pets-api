<?php

declare(strict_types=1);

namespace App\Pet\Infrastructure\Http\View;

use App\Shared\Domain\ValueObject\Money;
use App\Pet\Domain\ValueObject\VaccineType;
use App\Shared\Infrastructure\Http\View\View;

final class VaccinationView implements View, \JsonSerializable
{
    public Money $price;

    public function __construct(
        public string $publicId,
        public string $name,
        public ?string $description,
        public string $petPublicId,
        public string $petName,
        public string $veterinarianPublicId,
        public string $veterinarianName,
        public VaccineType $type,
        public \DateTime $applicationDate,
        public \DateTime $boosterDate,
        int $price,
        public string $manufacturer,
        public ?string $notes,
    ) {
        $this->price = Money::fromCents($price);
    }

    public function jsonSerialize(): array
    {
        return [
            'publicId' => $this->publicId,
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'applicationDate' => $this->applicationDate,
            'boosterDate' => $this->boosterDate,
            'price' => $this->price->toFloat(),
            'manufacturer' => $this->manufacturer,
            'notes' => $this->notes,
            'pet' => $this->buildPetView(),
            'veterinarian' => $this->buildVeterinarianView(),
        ];
    }

    private function buildPetView(): array
    {
        return [
            'publicId' => $this->petPublicId,
            'name' => $this->petName,
        ];
    }

    private function buildVeterinarianView(): array
    {
        return [
            'publicId' => $this->veterinarianPublicId,
            'name' => $this->veterinarianName,
        ];
    }
}
