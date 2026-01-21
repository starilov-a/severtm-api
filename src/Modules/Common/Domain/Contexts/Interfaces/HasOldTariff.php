<?php

namespace App\Modules\Common\Domain\Contexts\Interfaces;

use App\Modules\Common\Domain\Entity\Tariff;

interface HasOldTariff
{
    public function getOldTariff(): Tariff;
}