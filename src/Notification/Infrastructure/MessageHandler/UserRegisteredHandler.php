<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\MessageHandler;

use Symfony\Component\Messenger\MessageBusInterface;
use App\Notification\Infrastructure\Message\SendEmail;
use App\User\Domain\Message\UserRegistered;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UserRegisteredHandler
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(UserRegistered $message): void
    {
        $user = $message->getUser();
        $this->messageBus->dispatch(new SendEmail(
            to: $user->email(),
            from: SendEmail::SYSTEM_EMAIL,
            fromName: SendEmail::SYSTEM_EMAIL_NAME,
            subject: 'User registered successfully',
            htmlTemplate: 'emails/user_registered.html.twig',
            context: [
                'name' => $user->getFullName(),
                'email' => $user->email(),
            ],
        ));
    }
}
