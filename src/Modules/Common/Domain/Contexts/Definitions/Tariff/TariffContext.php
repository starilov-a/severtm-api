<?php

namespace App\Modules\Common\Domain\Contexts\Definitions\Tariff;

use App\Modules\Common\Domain\Contexts\Interfaces\HasMaster;
use App\Modules\Common\Domain\Contexts\Interfaces\HasTariff;
use App\Modules\Common\Domain\Contexts\Interfaces\HasWebAction;
use App\Modules\Common\Domain\Entity\Tariff;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\WebAction;

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