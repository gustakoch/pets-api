<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\MessageHandler;

use Twig\Environment;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use App\Notification\Infrastructure\Message\SendEmail;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

#[AsMessageHandler]
class SendEmailHandler
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly Environment $twig,
    ) {
    }

    public function __invoke(SendEmail $message): void
    {
        try {
            $email = (new TemplatedEmail())
            ->from(new Address($message->from, $message->fromName))
            ->subject($message->subject)
            ->context($message->context)
            ->html($this->twig->render($message->htmlTemplate, $message->context));

            if (\is_array($message->to)) {
                $email->to(...$message->to);
            } else {
                $email->to($message->to);
            }
            if ($message->text) {
                $email->text($message->text);
            }
            foreach ($message->attachments as $attachment) {
                $email->attachFromPath($attachment);
            }
            if ($message->bcc) {
                $email->bcc($message->bcc);
            }
            if ($message->additionalCC) {
                $email->cc($message->additionalCC);
            }

            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
        }
    }
}
