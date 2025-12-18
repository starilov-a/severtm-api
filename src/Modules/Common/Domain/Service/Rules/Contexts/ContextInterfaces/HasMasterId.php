<?php

namespace App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces;

interface HasMasterId
{
    public function getMasterId(): int;
}