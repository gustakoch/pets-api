<?php

declare(strict_types=1);

namespace App\Pet\Infrastructure\Http\View;

use App\Veterinarian\Domain\Veterinarian;
use Doctrine\Common\Collections\Collection;
use App\Shared\Infrastructure\Http\View\View;

final class PetView implements View, \JsonSerializable
{
    public function __construct(
        public string $id,
        public string $name,
        public string $specie,
        public \DateTime $birthDate,
        public string $color,
        public ?string $description,
        /**
         * @var Collection<int, Veterinarian>
         */
        public Collection $veterinarians,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'specie' => $this->specie,
            'birthDate' => $this->birthDate,
            'color' => $this->color,
            'description' => $this->description,
            'veterinarians' => $this->getVeterinariansView(),
        ];
    }

    private function getVeterinariansView(): array
    {
        return $this->veterinarians->map(static function ($veterinarian) {
            return [
                'id' => $veterinarian->getId()->publicId(),
                'name' => $veterinarian->getName(),
                'phone' => $veterinarian->getPhone(),
            ];
        })->toArray();
    }
}
