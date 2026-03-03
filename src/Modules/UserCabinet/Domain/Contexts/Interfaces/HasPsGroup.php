<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

use App\Modules\UserCabinet\Domain\Entity\PsGroup;

interface HasPsGroup
{
    public function getPsGroup(): PsGroup;
}