<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use Doctrine\DBAL\Types\Types;
use Symfony\Component\Uid\Ulid;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class BaseId
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(name: 'id', type: Types::INTEGER, unique: true)]
    protected int $id;

    #[ORM\Column(name: 'public_id', type: 'string', length: 26, unique: true)]
    protected string $publicId;

    public function __construct(?Ulid $publicId = null)
    {
        $this->publicId = (string) $publicId ?: (string) new Ulid();
    }

    public static function fromString(string $id): self
    {
        return new self(Ulid::fromString($id));
    }

    public function id(): int
    {
        return $this->id;
    }

    public function publicId(): string
    {
        return $this->publicId;
    }

    public function __toString(): string
    {
        return $this->publicId;
    }
}
