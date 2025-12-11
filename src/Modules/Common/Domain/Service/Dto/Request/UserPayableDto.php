<?php

namespace App\Modules\Common\Domain\Service\Dto\Request;

use App\Modules\Common\Domain\Entity\FinPeriod;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\UserServMode;
use App\Modules\Common\Domain\Service\Dto\Dto;

class UserPayableDto extends TypedWriteOffDto
{
    protected int $payable;

    public function getPayable(): int
    {
        return $this->payable;
    }

    public function setPayable(int $payable): void
    {
        $this->payable = $payable;
    }
}