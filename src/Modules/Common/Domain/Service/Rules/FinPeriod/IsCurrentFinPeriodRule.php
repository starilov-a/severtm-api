<?php

namespace App\Modules\Common\Domain\Service\Rules\FinPeriod;

use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasFinPeriod;
use App\Modules\Common\Domain\Service\Rules\Rule;
use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;

class IsCurrentFinPeriodRule extends Rule
{
    public function __construct(
        protected LoggerService $loggerService,
    ){}
    public function check(object $context): bool
    {
        if (!$context instanceof HasFinPeriod)
            throw new \LogicException('Wrong context passed to IsCurrentFinPeriodRule');

        if (!$context->getFinPeriod()->isCurrent()) {
            $this->loggerService->businessLog(new BusinessLogDto(
                $context->getMaster()->getId(),
                1,
                'Финансовый период не является текущим',
                true
            ));

            return false;
        }

        return true;
    }
}