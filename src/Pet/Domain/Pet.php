<?php

declare(strict_types=1);

namespace App\Pet\Domain;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Shared\Domain\Timestampable;
use App\Pet\Domain\ValueObject\PetId;
use App\Pet\Domain\ValueObject\Specie;
use App\Veterinarian\Domain\Veterinarian;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table('pets')]
#[ORM\HasLifecycleCallbacks]
class Pet
{
    use Timestampable;

    #[ORM\Embedded(class: PetId::class, columnPrefix: false)]
    private PetId $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 255)]
    private Specie $specie;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private \DateTimeInterface $birthDate;

    #[ORM\Column(length: 255)]
    private string $color;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;
    /**
     * @var Collection<int, Veterinarian>
     */
    #[ORM\ManyToMany(targetEntity: Veterinarian::class, inversedBy: 'pets')]
    private Collection $veterinarians;

    /**
     * @var Collection<int, Vaccination>
     */
    #[ORM\OneToMany(targetEntity: Vaccination::class, mappedBy: 'pet')]
    private Collection $vaccinations;

    public function __construct()
    {
        $this->id = new PetId();
        $this->initializeTimestampable();
        $this->veterinarians = new ArrayCollection();
        $this->vaccinations = new ArrayCollection();
    }

    public static function create(
        string $name,
        Specie $specie,
        \DateTime $birthDate,
        string $color,
        ?string $description,
        array $veterinarians,
    ): self {
        $pet = new self();
        $pet->name = $name;
        $pet->specie = $specie;
        $pet->birthDate = $birthDate;
        $pet->color = $color;
        $pet->description = $description;

        /** @var Veterinarian $veterinarian */
        foreach ($veterinarians as $veterinarian) {
            $pet->addVeterinarian($veterinarian);
        }

        return $pet;
    }

    public function update(
        string $name,
        Specie $specie,
        \DateTime $birthDate,
        string $color,
        ?string $description,
        array $veterinarians,
    ): self {
        $this->name = $name;
        $this->specie = $specie;
        $this->birthDate = $birthDate;
        $this->color = $color;
        $this->description = $description;
        $this->veterinarians = new ArrayCollection(
            array_unique($veterinarians, \SORT_REGULAR)
        );

        return $this;
    }

    private function getVeterinarianIds(array $veterinarians): array
    {
        return array_map(static fn (Veterinarian $vet) => $vet->getId(), $veterinarians);
    }

    public function getId(): PetId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSpecie(): Specie
    {
        return $this->specie;
    }

    public function setSpecie(Specie $specie): static
    {
        $this->specie = $specie;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): static
    {
        $this->birthDate = $birthDate;

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

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return Collection<int, Veterinarian>
     */
    public function getVeterinarians(): Collection
    {
        return $this->veterinarians;
    }

    public function addVeterinarian(Veterinarian $veterinarian): static
    {
        if (!$this->veterinarians->contains($veterinarian)) {
            $this->veterinarians->add($veterinarian);
        }

        return $this;
    }

    public function removeVeterinarian(Veterinarian $veterinarian): static
    {
        $this->veterinarians->removeElement($veterinarian);

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
            $vaccination->setPet($this);
        }

        return $this;
    }

    public function removeVaccination(Vaccination $vaccination): static
    {
        if ($this->vaccinations->removeElement($vaccination)) {
            // set the owning side to null (unless already changed)
            if ($vaccination->getPet() === $this) {
                $vaccination->setPet(null);
            }
        }

        return $this;
    }
}
