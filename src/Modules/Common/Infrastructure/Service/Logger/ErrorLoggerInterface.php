<?php

namespace App\Modules\Common\Infrastructure\Service\Logger;

use App\Modules\Common\Infrastructure\Service\Logger\Dto\ErrorLog;

interface ErrorLoggerInterface
{
    public function logError(ErrorLog $log): void;
}