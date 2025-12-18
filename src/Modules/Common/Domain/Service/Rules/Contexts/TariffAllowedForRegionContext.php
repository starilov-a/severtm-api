<?php

namespace App\Modules\Common\Domain\Service\Rules\Contexts;

use App\Modules\Common\Domain\Entity\Region;
use App\Modules\Common\Domain\Entity\Tariff;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasActionId;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasRegion;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasTariff;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUserId;

class TariffAllowedForRegionContext implements HasActionId, HasTariff, HasRegion, HasUserId
{
    public function __construct(
        protected int $userId,
        protected int $actionId,
        protected Tariff $tariff,
        protected Region $region,
    ) {}
    public function getActionId(): int
    {
        return $this->actionId;
    }

    public function getRegion(): Region
    {
        return $this->region;
    }

    public function getTariff(): Tariff
    {
        return $this->tariff;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}