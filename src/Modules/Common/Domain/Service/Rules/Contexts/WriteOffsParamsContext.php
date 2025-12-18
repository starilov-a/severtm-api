<?php

namespace App\Modules\Common\Domain\Service\Rules\Contexts;

use App\Modules\Common\Domain\Entity\FinPeriod;
use App\Modules\Common\Domain\Entity\UserServMode;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasActionId;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasIsAppliedWriteOff;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasIsRealWriteOff;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasPayableType;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasRefundFinPeriod;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUserServMode;

class WriteOffsParamsContext implements HasPayableType, HasUserServMode, HasRefundFinPeriod, HasIsRealWriteOff, HasIsAppliedWriteOff, HasActionId
{
    public function __construct(
        protected int $actionId,
        protected string $userPayableType,
        protected UserServMode $userServMode,
        protected FinPeriod $refundFinPeriod,
        protected bool $isAppliedWriteOff,
        protected bool $isRealWriteOff,
    ) {}

    /**
     * @return string
     */
    public function getWriteOffType(): string
    {
        return $this->userPayableType;
    }

    /**
     * @return FinPeriod
     */
    public function getRefundFinPeriod(): FinPeriod
    {
        return $this->refundFinPeriod;
    }

    /**
     * @return UserServMode
     */
    public function getUserServMode(): UserServMode
    {
        return $this->userServMode;
    }

    /**
     * @return bool
     */
    public function getIsAppliedWriteOff(): bool
    {
        return $this->isAppliedWriteOff;
    }

    /**
     * @return bool
     */
    public function getIsRealWriteOff(): bool
    {
        return $this->isRealWriteOff;
    }

    /**
     * @return int
     */
    public function getActionId(): int
    {
        return $this->actionId;
    }
}