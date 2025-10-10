<?php

namespace App\Modules\Common\Infrastructure\Service\Logger;

use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLogDto;

interface BusinessLoggerInterface
{
    public function businessLog(BusinessLogDto $log): void;
}