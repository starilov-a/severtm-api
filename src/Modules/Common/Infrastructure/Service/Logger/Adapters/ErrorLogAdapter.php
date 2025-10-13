<?php

namespace App\Modules\Common\Infrastructure\Service\Logger\Adapters;

use App\Modules\Common\Infrastructure\Service\Logger\Dto\ErrorLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\ErrorLoggerInterface;
use Monolog\Logger;

class ErrorLogAdapter implements ErrorLoggerInterface
{
    protected Logger $logger;
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function logError(ErrorLogDto $log): void
    {
        $this->logger->error($log->message, $log->context);
    }
}