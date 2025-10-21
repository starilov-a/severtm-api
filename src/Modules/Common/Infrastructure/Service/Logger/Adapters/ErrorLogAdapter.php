<?php

namespace App\Modules\Common\Infrastructure\Service\Logger\Adapters;

use App\Modules\Common\Infrastructure\Service\Logger\Dto\ErrorLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\ErrorLoggerInterface;
use Psr\Log\LoggerInterface;

class ErrorLogAdapter implements ErrorLoggerInterface
{
    protected LoggerInterface $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function logError(ErrorLogDto $log): void
    {
        dd($log);
        $this->logger->error($log->message, $log->context);
    }
}