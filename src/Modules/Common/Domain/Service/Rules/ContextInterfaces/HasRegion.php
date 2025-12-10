<?php

namespace App\Modules\Common\Domain\Service\Rules\ContextInterfaces;

use App\Modules\Common\Domain\Entity\Region;

interface HasRegion
{
    public function getRegion(): Region;

}