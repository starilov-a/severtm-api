<?php

namespace App\Modules\Common\Domain\Contexts\Interfaces;

use App\Modules\Common\Domain\Entity\UserPayableType;

interface HasPayableType
{
    public function getPayableType(): UserPayableType;
}