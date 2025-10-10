<?php

namespace App\Modules\Common\Infrastructure\Service\Logger;

interface ErrorLoggerInterface
{
    public function logError( string $message, array $context = [], ?\DateTimeImmutable $at = null): void;
}