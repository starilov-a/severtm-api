<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\PsGroup;

interface HasPsGroup
{
    public function getPsGroup(): PsGroup;
}