<?php

namespace App\Modules\Common\Domain\Service\Rules\Contexts;

use App\Modules\Common\Domain\Entity\FinPeriod;
use App\Modules\Common\Domain\Entity\ProdServMode;
use App\Modules\Common\Domain\Entity\Region;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\WebAction;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasActionId;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasBoolVar;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasMaster;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasModeUnitCount;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasNullbleVar;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasProdServMode;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasRegion;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUserId;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasWebAction;

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