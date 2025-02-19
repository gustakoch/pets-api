<?php

declare(strict_types=1);

namespace App\Shared\Application\Exception;

final class EntityValidationException extends \Exception
{
    private array $errors;

    public function __construct(
        array $errors,
        $message = 'There are some errors',
        $code = 422,
        ?\Exception $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
