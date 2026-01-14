<?php

namespace App\Modules\Common\Domain\Service\Rules\Contexts;

use App\Modules\Common\Domain\Entity\FinPeriod;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\UserPayableType;
use App\Modules\Common\Domain\Entity\UserServMode;
use App\Modules\Common\Domain\Entity\WebAction;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasFinPeriod;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasIsAppliedWriteOff;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasIsRealWriteOff;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasMaster;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasPayableType;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasRefundFinPeriodNullable;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUserServMode;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasWebAction;

class ShouldCreateUserPayableContext implements HasWebAction, HasMaster,  HasUser, HasFinPeriod, HasPayableType, HasUserServMode, HasRefundFinPeriodNullable, HasIsRealWriteOff, HasIsAppliedWriteOff
{
    public function __construct(
        protected WebAction         $webAction,
        protected User              $master,
        protected User              $user,
        protected FinPeriod         $finPeriod,
        protected UserPayableType   $userPayableType,
        protected UserServMode      $userServMode,
        protected ?FinPeriod        $refundFinPeriod,
        protected bool              $isAppliedWriteOff,
        protected bool              $isRealWriteOff,
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
    public function getPayableType(): UserPayableType
    {
        return $this->userPayableType;
    }

    /**
     * @return FinPeriod
     */
    public function getRefundFinPeriodNullable(): ?FinPeriod
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

    public function getWebAction(): WebAction
    {
        return $this->webAction;
    }
}
