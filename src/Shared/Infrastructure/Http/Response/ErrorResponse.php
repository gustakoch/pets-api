<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Http\Response;

final class ErrorResponse
{
    public ?string $message = '';
    public ?array $errors = [];

    public function __construct(string $message, array $errors)
    {
        $this->message = $message;
        $this->errors = $errors;
    }
}
