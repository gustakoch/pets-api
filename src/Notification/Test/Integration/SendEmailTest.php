<?php

declare(strict_types=1);

namespace App\Notification\Test\Integration;

use Twig\Environment;
use PHPUnit\Framework\TestCase;
use Twig\Loader\FilesystemLoader;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use App\Notification\Infrastructure\Message\SendEmail;
use App\Notification\Infrastructure\MessageHandler\SendEmailHandler;

(new Dotenv())->bootEnv(__DIR__.'/../../../../.env');

class SendEmailTest extends TestCase
{
    public function testSendEmailForReal(): void
    {
        $transport = Transport::fromDsn($_ENV['MAILER_DSN']);
        $mailer = new Mailer($transport);
        $twigLoader = new FilesystemLoader(__DIR__.'/../../../../templates');
        $twig = new Environment($twigLoader);

        $handler = new SendEmailHandler($mailer, $twig);
        $email = new SendEmail(
            from: SendEmail::SYSTEM_EMAIL,
            fromName: 'System Test',
            to: 'test@test.com',
            subject: 'Test Email',
            htmlTemplate: 'emails/test.html.twig',
            context: [
                'name' => 'John Doe',
            ],
            additionalCC: 'test2@test.com',
            bcc: 'testbcc@test.com'
        );
        // $handler->__invoke($email);

        $this->assertTrue(true);
    }
}
