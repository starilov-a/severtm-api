<?php

namespace App\Modules\UserCabinet\Infrastructure\Service\Logger;

use App\Modules\UserCabinet\Infrastructure\Service\Logger\Dto\ErrorLogDto;

interface ErrorLoggerInterface
{
    public function logError(ErrorLogDto $log): void;
}