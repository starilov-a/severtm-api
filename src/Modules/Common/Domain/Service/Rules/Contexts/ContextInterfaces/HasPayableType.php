<?php

namespace App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces;

interface HasPayableType
{
    public function getWriteOffType(): string;
}