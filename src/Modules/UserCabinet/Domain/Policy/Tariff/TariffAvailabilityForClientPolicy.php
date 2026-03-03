<?php

namespace App\Modules\UserCabinet\Domain\Policy\Tariff;

use App\Modules\Common\Policy\Policy;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasOldTariff;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasTariff;
use App\Modules\UserCabinet\Domain\Repository\TariffGroupRepository;

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