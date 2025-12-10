<?php

namespace App\Modules\Common\Domain\Service\Rules\WriteOff;

use App\Modules\Common\Domain\Entity\UserPayableType;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasPayableType;

class WriteOffsParamsContext implements HasPayableType
{
    public function __construct(
        protected UserPayableType $userPayableType
    ){}

    /**
     * @return UserPayableType
     */
    public function getWriteOffType(): UserPayableType
    {
        return $this->userPayableType;
    }
}