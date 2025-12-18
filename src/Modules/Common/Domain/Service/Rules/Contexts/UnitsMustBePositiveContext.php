<?php

namespace App\Modules\Common\Domain\Service\Rules\Contexts;

use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasActionId;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasModeUnitCount;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUserId;

class UnitsMustBePositiveContext implements HasActionId, HasModeUnitCount, HasUserId
{


    public function __construct(
        protected int $userId,
        protected int $actionId,
        protected int $modeUnitCount
    ) {}

    public function getActionId(): int
    {
        return $this->actionId;
    }

    public function getModeUnitCount(): int
    {
        return $this->modeUnitCount;
    }


    public function getUserId(): int
    {
        return $this->userId;
    }
}