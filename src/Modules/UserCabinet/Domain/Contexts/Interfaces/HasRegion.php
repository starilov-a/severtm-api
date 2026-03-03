<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

use App\Modules\UserCabinet\Domain\Entity\Region;

interface HasRegion
{
    public function getRegion(): Region;

}