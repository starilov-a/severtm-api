<?php

namespace App\Modules\UserCabinet\Infrastructure\Service\Logger;

use App\Modules\UserCabinet\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\UserCabinet\Infrastructure\Service\Logger\Dto\ErrorLogDto;

class LoggerService
{
    public function __construct(
        private ErrorLoggerInterface $errorLogger,
        private BusinessLoggerInterface $businessLogger
    ) {}

    public function businessLog(BusinessLogDto $log): void
    {
        $this->businessLogger->businessLog($log);
    }

    public function errorLog(ErrorLogDto $log): void
    {
        $this->errorLogger->logError($log);
    }
}