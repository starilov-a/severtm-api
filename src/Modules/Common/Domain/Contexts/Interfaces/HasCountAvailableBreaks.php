<?php

namespace App\Modules\Common\Domain\Contexts\Interfaces;

interface HasCountAvailableBreaks
{
    public function getCountAvailableBreaks(): int;
}