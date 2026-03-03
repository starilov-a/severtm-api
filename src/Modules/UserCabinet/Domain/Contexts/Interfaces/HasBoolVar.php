<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

interface HasBoolVar
{
    public function getBoolVar(): bool;
}