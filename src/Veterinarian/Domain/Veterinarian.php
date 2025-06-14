<?php

declare(strict_types=1);

namespace App\Veterinarian\Domain;

use App\Pet\Domain\Pet;
use Doctrine\DBAL\Types\Types;
use App\Pet\Domain\Vaccination;
use Doctrine\ORM\Mapping as ORM;
use App\Shared\Domain\Timestampable;
use Doctrine\Common\Collections\Collection;
use App\Veterinarian\Domain\ValueObject\Address;
use Doctrine\Common\Collections\ArrayCollection;
use App\Veterinarian\Domain\ValueObject\VeterinarianId;

#[ORM\Entity]
#[ORM\Table('veterinarians')]
#[ORM\HasLifecycleCallbacks]
class Veterinarian
{
    use Timestampable;

    #[ORM\Embedded(class: VeterinarianId::class, columnPrefix: false)]
    private VeterinarianId $id;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column]
    private bool $hasPhoneWhatsapp = false;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $specializations = null;

    #[ORM\Embedded(class: Address::class, columnPrefix: 'address_')]
    private Address $address;

    /**
     * @var Collection<int, Pet>
     */
    #[ORM\ManyToMany(targetEntity: Pet::class, mappedBy: 'veterinarians')]
    private Collection $pets;

    /**
     * @var Collection<int, Vaccination>
     */
    #[ORM\OneToMany(targetEntity: Vaccination::class, mappedBy: 'veterinarian')]
    private Collection $vaccinations;

    public function __construct()
    {
        $this->id = new VeterinarianId();
        $this->initializeTimestampable();
        $this->pets = new ArrayCollection();
        $this->vaccinations = new ArrayCollection();
    }

    public static function create(
        string $name,
        ?string $email,
        ?string $phone,
        bool $hasPhoneWhatsapp,
        ?string $specializations,
        Address $address,
    ): self {
        $veterinarian = new self();
        $veterinarian->name = $name;
        $veterinarian->email = $email;
        $veterinarian->phone = $phone;
        $veterinarian->hasPhoneWhatsapp = $hasPhoneWhatsapp;
        $veterinarian->specializations = $specializations;
        $veterinarian->address = $address;

        return $veterinarian;
    }

    public function update(
        string $name,
        ?string $email,
        ?string $phone,
        bool $hasPhoneWhatsapp,
        ?string $specializations,
        Address $address,
    ): void {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->hasPhoneWhatsapp = $hasPhoneWhatsapp;
        $this->specializations = $specializations;
        $this->address = $address;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getId(): VeterinarianId
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Pet>
     */
    public function getPets(): Collection
    {
        return $this->pets;
    }

    public function addPet(Pet $pet): static
    {
        if (!$this->pets->contains($pet)) {
            $this->pets->add($pet);
            $pet->addVeterinarian($this);
        }

        return $this;
    }

    public function removePet(Pet $pet): static
    {
        if ($this->pets->removeElement($pet)) {
            $pet->removeVeterinarian($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Vaccination>
     */
    public function getVaccinations(): Collection
    {
        return $this->vaccinations;
    }

    public function addVaccination(Vaccination $vaccination): static
    {
        if (!$this->vaccinations->contains($vaccination)) {
            $this->vaccinations->add($vaccination);
            $vaccination->setVeterinarian($this);
        }

        return $this;
    }

    public function removeVaccination(Vaccination $vaccination): static
    {
        if ($this->vaccinations->removeElement($vaccination)) {
            // set the owning side to null (unless already changed)
            if ($vaccination->getVeterinarian() === $this) {
                $vaccination->setVeterinarian(null);
            }
        }

        return $this;
    }
}
