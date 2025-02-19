<?php

declare(strict_types=1);

namespace App\Pet\Domain;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Shared\Domain\Timestampable;
use App\Shared\Domain\ValueObject\Money;
use App\Veterinarian\Domain\Veterinarian;
use App\Pet\Domain\ValueObject\VaccineType;
use App\Pet\Domain\ValueObject\VaccinationId;

#[ORM\Entity]
#[ORM\Table('vaccinations')]
#[ORM\HasLifecycleCallbacks]
class Vaccination
{
    use Timestampable;

    #[ORM\Embedded(class: VaccinationId::class, columnPrefix: false)]
    private VaccinationId $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\ManyToOne(inversedBy: 'vaccinations')]
    #[ORM\JoinColumn(nullable: false)]
    private Pet $pet;

    #[ORM\ManyToOne(inversedBy: 'vaccinations')]
    #[ORM\JoinColumn(nullable: false)]
    private Veterinarian $veterinarian;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private VaccineType $type;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTime $applicationDate;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTime $boosterDate;

    #[ORM\Column(type: Types::BIGINT)]
    private int $price;

    #[ORM\Column(length: 255)]
    private string $manufacturer;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    public function __construct()
    {
        $this->id = new VaccinationId();
        $this->initializeTimestampable();
    }

    public static function create(
        string $name,
        Pet $pet,
        Veterinarian $veterinarian,
        ?string $description,
        VaccineType $type,
        \DateTime $applicationDate,
        \DateTime $boosterDate,
        Money $price,
        string $manufacturer,
        string $notes,
    ): self {
        $vaccination = new self();
        $vaccination->name = $name;
        $vaccination->pet = $pet;
        $vaccination->veterinarian = $veterinarian;
        $vaccination->description = $description;
        $vaccination->type = $type;
        $vaccination->applicationDate = $applicationDate;
        $vaccination->boosterDate = $boosterDate;
        $vaccination->price = $price->toCents();
        $vaccination->manufacturer = $manufacturer;
        $vaccination->notes = $notes;

        return $vaccination;
    }

    public function update(
        string $name,
        Pet $pet,
        Veterinarian $veterinarian,
        ?string $description,
        VaccineType $type,
        \DateTime $applicationDate,
        \DateTime $boosterDate,
        Money $price,
        string $manufacturer,
        string $notes,
    ): self {
        $this->name = $name;
        $this->pet = $pet;
        $this->veterinarian = $veterinarian;
        $this->description = $description;
        $this->type = $type;
        $this->applicationDate = $applicationDate;
        $this->boosterDate = $boosterDate;
        $this->price = $price->toCents();
        $this->manufacturer = $manufacturer;
        $this->notes = $notes;

        return $this;
    }

    public function getId(): VaccinationId
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPet(): ?Pet
    {
        return $this->pet;
    }

    public function setPet(?Pet $pet): static
    {
        $this->pet = $pet;

        return $this;
    }

    public function getVeterinarian(): ?Veterinarian
    {
        return $this->veterinarian;
    }

    public function setVeterinarian(?Veterinarian $veterinarian): static
    {
        $this->veterinarian = $veterinarian;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): VaccineType
    {
        return $this->type;
    }

    public function setType(VaccineType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getApplicationDate(): ?\DateTime
    {
        return $this->applicationDate;
    }

    public function setApplicationDate(\DateTime $applicationDate): static
    {
        $this->applicationDate = $applicationDate;

        return $this;
    }

    public function getBoosterDate(): ?\DateTime
    {
        return $this->boosterDate;
    }

    public function setBoosterDate(\DateTime $boosterDate): static
    {
        $this->boosterDate = $boosterDate;

        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getManufacturer(): ?string
    {
        return $this->manufacturer;
    }

    public function setManufacturer(string $manufacturer): static
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }
}
