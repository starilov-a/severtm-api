<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

use App\Modules\UserCabinet\Domain\Entity\UserPayableType;

interface HasPayableType
{
    public function getPayableType(): UserPayableType;
}