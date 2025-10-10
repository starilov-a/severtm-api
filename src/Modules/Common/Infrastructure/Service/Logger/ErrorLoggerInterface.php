<?php

namespace App\Modules\Common\Infrastructure\Service\Logger;

use App\Modules\Common\Infrastructure\Service\Logger\Dto\ErrorLogDto;

interface ErrorLoggerInterface
{
    public function logError(ErrorLogDto $log): void;
}