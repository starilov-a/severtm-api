<?php

namespace App\Modules\Common\Infrastructure\Service\Logger;

use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLog;
use Psr\Log\LoggerInterface;

class LoggerService
{
    public function __construct(
        private ErrorLoggerInterface $errorLogger,
        private BusinessLoggerInterface $businessLogger
    ) {}

    public function log(BusinessLog $log): void
    {
        $this->businessLogger->log($log);
    }

    public function errorLog(): void
    {
        $this->errorLogger->logError();
    }
}