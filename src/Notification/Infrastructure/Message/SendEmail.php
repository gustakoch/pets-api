<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\Message;

class SendEmail
{
    public const SYSTEM_EMAIL = 'system@pets.com';
    public const SYSTEM_EMAIL_NAME = 'Pets System';

    public function __construct(
        public string|array $to,
        public string $from,
        public string $fromName,
        public string $subject,
        public string $htmlTemplate,
        public array $context = [],
        public ?string $text = null,
        public array $attachments = [],
        public ?string $bcc = null,
        public ?string $additionalCC = null,
    ) {
    }
}
