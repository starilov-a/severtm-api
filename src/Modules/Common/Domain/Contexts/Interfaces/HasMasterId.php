<?php

namespace App\Modules\Common\Domain\Contexts\Interfaces;

interface HasMasterId
{
    public function getMasterId(): int;
}