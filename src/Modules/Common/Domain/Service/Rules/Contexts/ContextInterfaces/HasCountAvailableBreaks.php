<?php

namespace App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces;

interface HasCountAvailableBreaks
{
    public function getCountAvailableBreaks(): int;
}