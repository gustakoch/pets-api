<?php

declare(strict_types=1);

namespace App\Pet\Application\Command\Pet;

use Symfony\Component\Uid\Ulid;
use App\Pet\Domain\ValueObject\PetId;
use App\Pet\Domain\ValueObject\Specie;
use App\Pet\Application\Validator\PetDoesNotExist;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Validator\Constraints as Assert;
use App\Veterinarian\Domain\ValueObject\VeterinarianId;
use OpenApi\Attributes as OA;

final class Update
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
        #[Assert\NotBlank]
        #[PetDoesNotExist]
        #[Ignore]
        public PetId $id,
        string $specie,
        string $birthDate,
        array $veterinarianIds,
    ) {
        $this->specie = Specie::from($specie);
        $this->birthDate = new \DateTime($birthDate);
        $this->veterinarianIds = array_map(static fn ($id) => new VeterinarianId(new Ulid($id)), $veterinarianIds);
    }
}
