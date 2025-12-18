<?php

namespace App\Modules\Common\Domain\Service\Rules\Contexts;

use App\Modules\Common\Domain\Entity\FinPeriod;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\UserServMode;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasFinPeriod;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasIsAppliedWriteOff;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasIsRealWriteOff;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasMaster;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasPayableType;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasRefundFinPeriod;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUserServMode;

class ShouldMakeWriteOffContext implements HasUser, HasFinPeriod, HasPayableType, HasUserServMode, HasRefundFinPeriod, HasIsRealWriteOff, HasIsAppliedWriteOff, HasMaster
{
    public function __construct(
        protected User         $master,
        protected User         $user,
        protected FinPeriod    $finPeriod,
        protected string       $userPayableType,
        protected UserServMode $userServMode,
        protected ?FinPeriod    $refundFinPeriod,
        protected bool         $isAppliedWriteOff,
        protected bool         $isRealWriteOff,
    ) {}

    /**
     * @return FinPeriod
     */
    public function getFinPeriod(): FinPeriod
    {
        return $this->finPeriod;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
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
    public function getMaster(): User
    {
        return $this->master;
    }
}
