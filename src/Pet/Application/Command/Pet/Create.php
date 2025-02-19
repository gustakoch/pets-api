<?php

declare(strict_types=1);

namespace App\Pet\Application\Command\Pet;

use OpenApi\Attributes as OA;
use Symfony\Component\Uid\Ulid;
use App\Pet\Domain\ValueObject\Specie;
use Symfony\Component\Validator\Constraints as Assert;
use App\Veterinarian\Domain\ValueObject\VeterinarianId;

final class Create
{
    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotBlank]
    public Specie $specie;

    #[Assert\NotBlank]
    public \DateTime $birthDate;

    #[Assert\NotBlank]
    public string $color;

    public ?string $description = null;

    #[Assert\All([new Assert\Ulid()])]
    #[OA\Property(
        type: 'array',
        items: new OA\Items(type: 'string'),
        example: ['01GZ9EG6J5N4F1Z6KAW4BTGPV4']
    )]
    public array $veterinarianIds;

    public function __construct(
        string $name,
        string $specie,
        string $birthDate,
        string $color,
        array $veterinarianIds,
    ) {
        $this->name = $name;
        $this->specie = Specie::from($specie);
        $this->birthDate = new \DateTime($birthDate);
        $this->color = $color;
        $this->veterinarianIds = array_map(static fn ($id) => new VeterinarianId(new Ulid($id)), $veterinarianIds);
    }
}
