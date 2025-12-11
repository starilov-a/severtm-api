<?php

namespace App\Modules\Common\Domain\Service\Rules\Chains;

use App\Modules\Common\Domain\Entity\FinPeriod;
use App\Modules\Common\Domain\Entity\ProdServMode;
use App\Modules\Common\Domain\Entity\Region;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasActionId;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasBoolVar;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasModeUnitCount;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasNullbleVar;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasProdServMode;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasRegion;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasUserId;

class AddServiceModeContext implements HasUserId, HasActionId, HasProdServMode, HasRegion, HasBoolVar, HasNullbleVar, HasModeUnitCount
{
    public function __construct(
        protected int $userId,
        protected int $actionId,
        protected ?FinPeriod $finPeriod,
        protected ProdServMode $mode,
        protected int $jurStatus,
        protected Region $region,
        protected int $modeUnitCount
    )
    {

    }
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

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getModeUnitCount(): int
    {
        return $this->modeUnitCount;
    }

    public function getRegion(): Region
    {
        return $this->region;
    }
}