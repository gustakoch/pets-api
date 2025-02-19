<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

interface JsonResponseException
{
    public function code(): int;

    public function message(): string;
}
