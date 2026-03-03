<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

use App\Modules\UserCabinet\Domain\Entity\Tariff;

interface HasOldTariff
{
    public function getOldTariff(): Tariff;
}