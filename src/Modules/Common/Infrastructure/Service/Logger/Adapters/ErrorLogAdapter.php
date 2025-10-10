<?php

namespace App\Modules\Common\Infrastructure\Service\Logger\Adapters;

use App\Modules\Common\Infrastructure\Service\Logger\ErrorLoggerInterface;

class ErrorLogAdapter implements ErrorLoggerInterface
{

    public function logError(string $message, array $context = [], ?\DateTimeImmutable $at = null): void
    {
        // TODO: Нужно что нибудь придумать
    }
}