<?php

namespace App\Modules\Common\Domain\Service\Rules\ProdServModes;

use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasActionId;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasMasterId;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasNullbleVar;

class FinPeriodMustBeIssetContext implements HasNullbleVar, HasMasterId, HasActionId
{
    protected ?int $finPeriodId;
    protected int $masterId;
    protected int $actionId;

    public function __construct(int $masterId, int $actionId, ?int $finPeriodId)
    {
        $this->finPeriodId = $finPeriodId;
        $this->actionId = $actionId;
        $this->masterId = $masterId;
    }

    public function getActionId(): int
    {
        return $this->actionId;
    }

    public function getMasterId(): int
    {
        return $this->masterId;
    }

    public function getNullbleVar(): ?int
    {
        return $this->finPeriodId;
    }
}