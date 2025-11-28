<?php

namespace App\Modules\Common\Domain\Service\Rules\ProdServModes;

use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasActionId;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasMasterId;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasModeUnitCount;

class UnitsMustBePositiveContext implements HasMasterId, HasActionId, HasModeUnitCount
{
    protected int $masterId;
    protected int $actionId;
    protected int $modeUnitCount;

    public function __construct(int $masterId, int $actionId, int $modeUnitCount)
    {
        $this->masterId = $masterId;
        $this->actionId = $actionId;
        $this->modeUnitCount = $modeUnitCount;
    }

    public function getMasterId(): int
    {
        return $this->masterId;
    }
    public function getActionId(): int
    {
        return $this->actionId;
    }

    public function getModeUnitCount(): int
    {
        return $this->modeUnitCount;
    }
}