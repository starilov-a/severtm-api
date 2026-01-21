<?php

namespace App\Modules\Common\Domain\Contexts\Interfaces;

use App\Modules\Common\Domain\Entity\Region;

interface HasRegion
{
    public function getRegion(): Region;

}