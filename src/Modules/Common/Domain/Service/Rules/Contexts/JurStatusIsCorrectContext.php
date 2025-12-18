<?php

namespace App\Modules\Common\Domain\Service\Rules\Contexts;

use App\Modules\Common\Domain\Entity\ProdServMode;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasActionId;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasBoolVar;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasProdServMode;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUserId;

class JurStatusIsCorrectContext implements HasUserId, HasActionId, HasProdServMode, HasBoolVar
{
    public function __construct(
        protected int $userId,
        protected int $actionId,
        protected ProdServMode $prodServMode,
        protected int $jurStatus,
    ) {}

    public function getUserId(): int
    {
        return $this->userId;
    }
    public function getActionId(): int
    {
        return $this->actionId;
    }

    public function getMode(): ProdServMode
    {
        return $this->prodServMode;
    }

    public function getBoolVar(): bool
    {
        return (bool)$this->jurStatus;
    }
}