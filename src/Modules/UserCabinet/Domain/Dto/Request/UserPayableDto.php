<?php

namespace App\Modules\UserCabinet\Domain\Dto\Request;

use App\Modules\Common\Domain\Service\Dto\Request\TypedWriteOffDto;

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