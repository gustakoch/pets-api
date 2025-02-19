<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Security;

use App\User\Domain\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

final class AuthenticationListener
{
    public function __construct(
        private readonly JWTEncoderInterface $jwtEncoder,
    ) {
    }

    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        /** @var User $user */
        $user = $event->getUser();
        $data = $event->getData();
        $event->setData([
            'status' => 'success',
            'userId' => $user->getId()->publicId(),
            'userStatus' => $user->status()->value(),
            'accessToken' => $data['token'],
            'refreshToken' => $data['refreshToken'],
        ]);
    }

    public function onAuthenticationFailure(AuthenticationFailureEvent $event): void
    {
        $event->setResponse(
            new JsonResponse([
                'status' => 'failed',
                'message' => 'Invalid credentials',
            ], Response::HTTP_UNAUTHORIZED)
        );
    }

    public function onJWTInvalid(JWTInvalidEvent $event): void
    {
        $event->setResponse(
            new JsonResponse([
                'status' => 'failed',
                'message' => 'Invalid token',
            ], Response::HTTP_UNAUTHORIZED)
        );
    }

    public function onJWTExpired(JWTExpiredEvent $event): void
    {
        $event->setResponse(
            new JsonResponse([
                'message' => 'Your token is expired, please renew it',
            ], Response::HTTP_UNAUTHORIZED)
        );
    }
}
