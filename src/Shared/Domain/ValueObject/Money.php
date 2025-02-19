<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

final class Money
{
    private int $amountInCents;

    public function __construct(int $amountInCents)
    {
        $this->amountInCents = $amountInCents;
    }

    public static function fromFloat(float $value): self
    {
        return new self((int) round($value * 100));
    }

    public static function fromCents(int $cents): self
    {
        return new self($cents);
    }

    public function toFloat(): float
    {
        return $this->amountInCents / 100;
    }

    public function toCents(): int
    {
        return $this->amountInCents;
    }

    public function __toString(): string
    {
        return number_format($this->toFloat(), 2, '.', '');
    }
}
