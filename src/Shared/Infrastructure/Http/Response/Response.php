<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Http\Response;

use Psr\Log\LoggerInterface;
use App\Shared\Infrastructure\Http\View\View;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use App\Shared\Application\Exception\EntityValidationException;

final readonly class Response
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function view(View|array|null $data = [], int $code = 200): JsonResponse
    {
        return null !== $data
            ? new JsonResponse($this->serialize($data), $code, json: true)
            : new JsonResponse();
    }

    public function list(array $data = [], int $code = 200): JsonResponse
    {
        return new JsonResponse($this->serialize($data), $code, json: true);
    }

    public function error(\Exception $exception): JsonResponse
    {
        $errors = match (true) {
            $exception instanceof EntityValidationException => $exception->getErrors(),
            default => [],
        };
        if (!$exception instanceof EntityValidationException) {
            $this->logger->critical($exception->getMessage());
        }
        $statusCode = $exception->getCode() >= 400 ? $exception->getCode() : 500;

        return new JsonResponse(
            new ErrorResponse(
                $exception->getMessage(),
                $errors
            ),
            $statusCode
        );
    }

    private function serialize(View|array|null $data): string
    {
        return $this->serializer->serialize($data, 'json', ['datetime_format' => 'Y-m-d']);
    }
}
