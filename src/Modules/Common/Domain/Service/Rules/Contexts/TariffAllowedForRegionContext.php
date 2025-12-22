<?php

namespace App\Modules\Common\Domain\Service\Rules\Contexts;

use App\Modules\Common\Domain\Entity\Region;
use App\Modules\Common\Domain\Entity\Tariff;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasRegion;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasTariff;

class TariffAllowedForRegionContext implements ContextInterfaces\HasTariff, ContextInterfaces\HasRegion
{
    public function __construct(
        protected Tariff $tariff,
        protected Region $region,
    ) {}

    public function getTariff(): Tariff
    {
        return $this->tariff;
    }

    public function getRegion(): Region
    {
        return $this->region;
    }
}