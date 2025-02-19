<?php

declare(strict_types=1);

namespace App\User\Domain\ValueObject;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class Password
{
    #[ORM\Column(length: 255, type: Types::STRING)]
    private string $password;

    #[ORM\Column(length: 255, type: Types::DATETIME_MUTABLE)]
    private \DateTime $expiredAt;

    public static function encode(string $plainPassword): self
    {
        $password = new self();
        $password->password = password_hash($plainPassword, \PASSWORD_ARGON2I);
        $password->expiredAt = new \DateTime('+90 days');

        return $password;
    }

    public function hash(): string
    {
        return $this->password;
    }

    public function expiredAt(): \DateTime
    {
        return $this->expiredAt;
    }

    public function isExpired(): bool
    {
        return $this->expiredAt < new \DateTime();
    }

    public function verify(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->password);
    }
}
