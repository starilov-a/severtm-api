<?php

namespace App\Modules\Common\Domain\Contexts\Definitions\Tariff;

use App\Modules\Common\Domain\Contexts\Interfaces\HasOldTariff;
use App\Modules\Common\Domain\Contexts\Interfaces\HasTariff;
use App\Modules\Common\Domain\Entity\Tariff;

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