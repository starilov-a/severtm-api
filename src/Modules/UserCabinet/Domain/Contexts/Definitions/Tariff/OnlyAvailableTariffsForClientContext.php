<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Definitions\Tariff;

use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasOldTariff;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasTariff;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\Tariff;

class OnlyAvailableTariffsForClientContext implements HasTariff, HasOldTariff
{
    public function __construct(
        protected Tariff $tariff,
        protected Tariff $oldTariff,
    ) {}
    public function getTariff(): Tariff
    {
        return $this->tariff;
    }

    public function getOldTariff(): Tariff
    {
        return $this->oldTariff;
    }
}