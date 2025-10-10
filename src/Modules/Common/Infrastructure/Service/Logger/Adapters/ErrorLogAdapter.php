<?php

namespace App\Modules\Common\Infrastructure\Service\Logger\Adapters;

use App\Modules\Common\Infrastructure\Service\Logger\Dto\ErrorLog;
use App\Modules\Common\Infrastructure\Service\Logger\ErrorLoggerInterface;

class ErrorLogAdapter implements ErrorLoggerInterface
{

    public function logError(ErrorLog $log): void
    {
        // TODO: Нужно что нибудь придумать
    }
}