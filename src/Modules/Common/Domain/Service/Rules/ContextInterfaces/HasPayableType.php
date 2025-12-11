<?php

namespace App\Modules\Common\Domain\Service\Rules\ContextInterfaces;

use App\Modules\Common\Domain\Entity\UserPayableType;

interface HasPayableType
{
    public function getWriteOffType(): string;
}