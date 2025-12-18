<?php

namespace App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces;

interface HasBoolVar
{
    public function getBoolVar(): bool;
}