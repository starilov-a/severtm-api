<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

interface HasIsRealWriteOff
{
    public function getIsRealWriteOff(): bool;
}