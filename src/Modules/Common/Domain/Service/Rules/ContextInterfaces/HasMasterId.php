<?php

namespace App\Modules\Common\Domain\Service\Rules\ContextInterfaces;

interface HasMasterId
{
    public function getMasterId(): int;
}