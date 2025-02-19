<?php

declare(strict_types=1);

namespace App\User\Domain\Exception;

use App\Shared\Domain\Exception\JsonResponseException;

final class UserInactiveException extends \RuntimeException implements JsonResponseException
{
    public function __construct(string $message = 'User account is inactive', int $statusCode = 403, ?\Throwable $previous = null)
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
