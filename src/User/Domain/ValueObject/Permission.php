<?php

declare(strict_types=1);

namespace App\User\Domain\ValueObject;

use App\User\Domain\Collection\Permissions;

final class Permission
{
    public function __construct(
        private string $value,
    ) {
        $this->assertSupport($this->value);
    }

    private function assertSupport(string $value): void
    {
        if (!\in_array($value, Permissions::AVAILABLE)) {
            throw new \Exception('Unsupported permission');
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
