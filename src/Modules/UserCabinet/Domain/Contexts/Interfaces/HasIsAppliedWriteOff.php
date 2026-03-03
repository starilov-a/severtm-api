<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

interface HasIsAppliedWriteOff
{
    public function getIsAppliedWriteOff(): bool;
}