<?php

namespace App\Modules\Common\Domain\Contexts\Interfaces;

use App\Modules\Common\Domain\Entity\UserServMode;

interface HasUserServMode
{
    public function getUserServMode(): UserServMode;
}