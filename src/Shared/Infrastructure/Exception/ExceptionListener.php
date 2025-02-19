<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Shared\Domain\Exception\JsonResponseException;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

final class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof JsonResponseException) {
            $event->setResponse(new JsonResponse([
                'status' => 'failed',
                'message' => $exception->message(),
            ], $exception->code()));
        }
    }
}
