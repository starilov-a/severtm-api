<?php

namespace App\Modules\Common\Domain\Service\Rules\ContextInterfaces;

use App\Modules\Common\Domain\Entity\UserServMode;

interface HasRealStatusWriteOff
{
    public function getRealStatusWrite(): bool;
}