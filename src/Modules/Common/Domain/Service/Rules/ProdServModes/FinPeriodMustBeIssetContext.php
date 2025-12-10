<?php

namespace App\Modules\Common\Domain\Service\Rules\ProdServModes;

use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasActionId;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasNullbleVar;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasUserId;

class FinPeriodMustBeIssetContext implements HasNullbleVar, HasUserId, HasActionId
{
    public function __construct(
        protected int $userId,
        protected int $actionId,
        protected ?int $finPeriodId
    ){}

    public function getActionId(): int
    {
        return $this->actionId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getNullbleVar(): ?int
    {
        return $this->finPeriodId;
    }
}