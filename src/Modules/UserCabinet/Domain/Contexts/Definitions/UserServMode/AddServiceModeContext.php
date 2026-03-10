<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Definitions\UserServMode;

use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasBoolVar;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasMaster;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasModeUnitCount;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasNullbleVar;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasProdServMode;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasRegion;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasWebAction;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\FinPeriod;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\ProdServMode;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\Region;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\WebAction;

class AddServiceModeContext implements HasWebAction, HasMaster, HasProdServMode, HasRegion, HasBoolVar, HasNullbleVar, HasModeUnitCount
{
    public function __construct(
        protected WebAction $webAction,
        protected User $master,
        protected int $actionId,
        protected ?FinPeriod $finPeriod,
        protected ProdServMode $mode,
        protected int $jurStatus,
        protected Region $region,
        protected int $modeUnitCount
    ) {}
    public function getActionId(): int
    {
        return $this->actionId;
    }

    public function getBoolVar(): bool
    {
        return (bool)$this->jurStatus;
    }

    public function getNullbleVar(): mixed
    {
        return $this->finPeriod;
    }

    public function getMode(): ProdServMode
    {
        return $this->mode;
    }

    public function getModeUnitCount(): int
    {
        return $this->modeUnitCount;
    }

    public function getRegion(): Region
    {
        return $this->region;
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