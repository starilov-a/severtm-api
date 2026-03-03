<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

interface HasCountAvailableBreaks
{
    public function getCountAvailableBreaks(): int;
}