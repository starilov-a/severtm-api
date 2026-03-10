<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Definitions\Tariff;

use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasMaster;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasTariff;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasWebAction;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\Tariff;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\WebAction;

class TariffContext implements HasWebAction, HasMaster, HasTariff
{
    public function __construct(
        protected WebAction $webAction,
        protected User  $master,
        protected Tariff $tariff,
    ) {}

    public function getTariff(): Tariff
    {
        return $this->tariff;
    }

    public function getMaster(): User
    {
        return $this->master;
    }

    public function getWebAction(): WebAction
    {
        return $this->webAction;
    }
}