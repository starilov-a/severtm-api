<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

use App\Modules\UserCabinet\Domain\Entity\Tariff;

interface HasTariff
{
    public function getTariff(): Tariff;
}