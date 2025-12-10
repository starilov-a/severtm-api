<?php

namespace App\Modules\Common\Domain\Service\Rules\Chains;

use App\Modules\Common\Domain\Entity\FinPeriod;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasFinPeriod;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasUser;

class ShouldMakeWriteOffContext implements HasUser, HasFinPeriod
{
    public function __construct(
        protected User $user,
        protected FinPeriod $finPeriod
    ){}

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
}