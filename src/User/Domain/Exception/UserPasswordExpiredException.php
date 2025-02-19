<?php

declare(strict_types=1);

namespace App\User\Domain\Exception;

use App\Shared\Domain\Exception\JsonResponseException;

final class UserPasswordExpiredException extends \RuntimeException implements JsonResponseException
{
    public function __construct(string $message = 'Your password has expired', int $statusCode = 403, ?\Throwable $previous = null)
    {
        parent::__construct($message, $statusCode, $previous);
    }

    public function code(): int
    {
        return $this->code;
    }

    public function message(): string
    {
        return $this->message;
    }
}
