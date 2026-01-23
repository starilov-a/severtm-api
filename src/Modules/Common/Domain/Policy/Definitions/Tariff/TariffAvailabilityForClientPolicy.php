<?php

namespace App\Modules\Common\Domain\Policy\Definitions\Tariff;

use App\Modules\Common\Domain\Contexts\Interfaces\HasOldTariff;
use App\Modules\Common\Domain\Contexts\Interfaces\HasTariff;
use App\Modules\Common\Domain\Policy\Policy;
use App\Modules\Common\Domain\Repository\TariffGroupRepository;

class TariffAvailabilityForClientPolicy extends Policy
{

    public function __construct(
        protected TariffGroupRepository $tariffGroupRepo,
    ) {}
    public function isAllowed(object $context): bool
    {
        if (!($context instanceof HasTariff) || !($context instanceof HasOldTariff))
            throw new \LogicException('Wrong context passed to TariffAvailabilityForClientPolicy');

        // 1. Цена должна быть выше, чем текущая
        if (!($context->getTariff()->getPrice() > $context->getOldTariff()->getPrice()))
            return false;

        return true;
    }
}