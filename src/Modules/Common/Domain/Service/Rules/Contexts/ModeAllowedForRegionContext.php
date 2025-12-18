<?php

namespace App\Modules\Common\Domain\Service\Rules\Contexts;

use App\Modules\Common\Domain\Entity\ProdServMode;
use App\Modules\Common\Domain\Entity\Region;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasActionId;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasProdServMode;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasRegion;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUserId;

class ModeAllowedForRegionContext implements HasActionId, HasProdServMode, HasRegion, HasUserId
{
    public function __construct(
        protected int $userId,
        protected int $actionId,
        protected ProdServMode $mode,
        protected Region $region,
    ) {}

    public function getActionId(): int
    {
        return $this->actionId;
    }

    public function getMode(): ProdServMode
    {
        return $this->mode;
    }

    public function getRegion(): Region
    {
        return $this->region;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}