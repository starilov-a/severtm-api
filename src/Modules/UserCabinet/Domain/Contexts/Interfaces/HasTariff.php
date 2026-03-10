<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\Tariff;

interface HasTariff
{
    public function getTariff(): Tariff;
}