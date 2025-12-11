<?php

namespace App\Modules\Common\Domain\Service\Rules\ContextInterfaces;

use App\Modules\Common\Domain\Entity\User;

interface HasMaster
{
    public function getMaster(): User;
}