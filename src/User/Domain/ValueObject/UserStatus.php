<?php

declare(strict_types=1);

namespace App\User\Domain\ValueObject;

enum UserStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Expired = 'expired';

    public static function values(): array
    {
        return array_map(static fn (self $case) => $case->value, self::cases());
    }

    public function isActive(): bool
    {
        return $this->value === self::Active->value;
    }

    public function isInactive(): bool
    {
        return $this->value === self::Inactive->value;
    }

    public function isExpired(): bool
    {
        return $this->value === self::Expired->value;
    }

    public function value(): string
    {
        return $this->value;
    }
}
