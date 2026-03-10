<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\WebAction;

interface HasWebAction
{
    public function getWebAction(): WebAction;
}