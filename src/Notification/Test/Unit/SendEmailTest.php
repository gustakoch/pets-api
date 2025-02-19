<?php

declare(strict_types=1);

namespace App\Notification\Test\Unit;

use Twig\Environment;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use App\Notification\Infrastructure\Message\SendEmail;
use App\Notification\Infrastructure\MessageHandler\SendEmailHandler;

class SendEmailTest extends TestCase
{
    public function testSendEmail(): void
    {
        $mailerMock = $this->createMock(MailerInterface::class);
        $mailerMock->expects($this->once())
            ->method('send')
            ->with($this->isInstanceOf(Email::class));

        $handler = new SendEmailHandler($mailerMock, $this->createMock(Environment::class));
        $email = new SendEmail(
            from: 'system@example.com',
            fromName: 'System Test',
            to: 'test@example.com',
            subject: 'Test Email',
            htmlTemplate: 'emails/test.html.twig',
            context: [
                'name' => 'John Doe',
            ],
            additionalCC: 'test2@example.com',
            bcc: 'testbcc@example.com'
        );
        $handler->__invoke($email);
    }
}
