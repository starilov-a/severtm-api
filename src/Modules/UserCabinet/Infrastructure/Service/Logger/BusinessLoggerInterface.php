<?php

namespace App\Modules\UserCabinet\Infrastructure\Service\Logger;

use App\Modules\UserCabinet\Infrastructure\Service\Logger\Dto\BusinessLogDto;

interface BusinessLoggerInterface
{
    public function businessLog(BusinessLogDto $log): void;
}