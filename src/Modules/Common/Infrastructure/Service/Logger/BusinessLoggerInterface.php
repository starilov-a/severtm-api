<?php

namespace App\Modules\Common\Infrastructure\Service\Logger;

use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLog;

interface BusinessLoggerInterface
{
    public function log(BusinessLog $log): void;
}