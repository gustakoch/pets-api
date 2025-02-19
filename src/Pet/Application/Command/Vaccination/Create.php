<?php

declare(strict_types=1);

namespace App\Pet\Application\Command\Vaccination;

use Symfony\Component\Uid\Ulid;
use App\Pet\Domain\ValueObject\PetId;
use App\Shared\Domain\ValueObject\Money;
use App\Pet\Domain\ValueObject\VaccineType;
use App\Pet\Application\Validator\PetDoesNotExist;
use Symfony\Component\Validator\Constraints as Assert;
use App\Veterinarian\Domain\ValueObject\VeterinarianId;
use App\Pet\Application\Validator\VeterinarianDoesNotExist;

final class Create
{
    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotBlank]
    #[PetDoesNotExist]
    public PetId $petId;

    #[Assert\NotBlank]
    #[VeterinarianDoesNotExist]
    public VeterinarianId $veterinarianId;

    public ?string $description = null;

    #[Assert\NotBlank]
    public VaccineType $type;

    #[Assert\NotBlank]
    public \DateTime $applicationDate;

    #[Assert\NotBlank]
    public \DateTime $boosterDate;

    #[Assert\NotBlank]
    public Money $price;

    #[Assert\NotBlank]
    public string $manufacturer;

    public ?string $notes = null;

    public function __construct(
        string $petId,
        string $veterinarianId,
        string $type,
        string $applicationDate,
        string $boosterDate,
        float $price,
    ) {
        $this->petId = new PetId(new Ulid($petId));
        $this->veterinarianId = new VeterinarianId(new Ulid($veterinarianId));
        $this->type = VaccineType::from($type);
        $this->applicationDate = new \DateTime($applicationDate);
        $this->boosterDate = new \DateTime($boosterDate);
        $this->price = Money::fromFloat($price);
    }
}
