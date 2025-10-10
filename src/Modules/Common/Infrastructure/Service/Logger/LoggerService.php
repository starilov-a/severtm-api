<?php

namespace App\Modules\Common\Infrastructure\Service\Logger;

use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLog;
use App\Modules\Common\Infrastructure\Service\Logger\Dto\ErrorLog;
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

    public function errorLog(ErrorLog $log): void
    {
        //TODO: реализовать
        $this->errorLogger->logError($log);
    }
}