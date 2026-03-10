<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Definitions\Tariff;

use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasMaster;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasOldTariff;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasRegion;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasTariff;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasUser;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasWebAction;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\Region;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\Tariff;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\WebAction;

class ChangeTariffContext implements HasWebAction, HasMaster, HasUser, HasTariff, HasOldTariff, HasRegion
{
    public function __construct(
        protected WebAction $webAction,
        protected User  $master,
        protected User $user,
        protected Tariff $tariff,
        protected Tariff $oldTariff,
        protected Region $region,
    ) {}

    public function getOldTariff(): Tariff
    {
        return $this->oldTariff;
    }

    public function getWebAction(): WebAction
    {
        return $this->webAction;
    }

    public function setWebAction(WebAction $webAction): void
    {
        $this->webAction = $webAction;
    }

    public function getMaster(): User
    {
        return $this->master;
    }

    public function setMaster(User $master): void
    {
        $this->master = $master;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getTariff(): Tariff
    {
        return $this->tariff;
    }

    public function setTariff(Tariff $tariff): void
    {
        $this->tariff = $tariff;
    }

    public function getRegion(): Region
    {
        return $this->region;
    }
}