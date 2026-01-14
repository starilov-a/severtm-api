<?php

namespace App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces;

use App\Modules\Common\Domain\Entity\UserPayableType;

interface HasPayableType
{
    public function getPayableType(): UserPayableType;
}