<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

use App\Modules\UserCabinet\Domain\Entity\WebAction;

interface HasWebAction
{
    public function getWebAction(): WebAction;
}