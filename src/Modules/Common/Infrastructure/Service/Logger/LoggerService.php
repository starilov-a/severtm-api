<?php

namespace App\Modules\Common\Infrastructure\Service\Logger;

use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\Dto\ErrorLogDto;
use Psr\Log\LoggerInterface;

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