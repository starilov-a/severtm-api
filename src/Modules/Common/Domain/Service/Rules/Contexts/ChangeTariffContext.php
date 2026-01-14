<?php

namespace App\Modules\Common\Domain\Service\Rules\Contexts;

use App\Modules\Common\Domain\Entity\Region;
use App\Modules\Common\Domain\Entity\Tariff;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\WebAction;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasMaster;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasOldTariff;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasRegion;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasTariff;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasWebAction;

class ChangeTariffContext implements HasWebAction, HasMaster, HasUser, HasTariff, HasOldTariff
{
    public function __construct(
        protected WebAction $webAction,
        protected User  $master,
        protected User $user,
        protected Tariff $tariff,
        protected Tariff $oldTariff,
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

}