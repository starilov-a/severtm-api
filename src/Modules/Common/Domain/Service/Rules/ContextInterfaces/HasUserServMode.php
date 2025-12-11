<?php

namespace App\Modules\Common\Domain\Service\Rules\ContextInterfaces;

use App\Modules\Common\Domain\Entity\UserServMode;

interface HasUserServMode
{
    public function getUserServMode(): UserServMode;
}