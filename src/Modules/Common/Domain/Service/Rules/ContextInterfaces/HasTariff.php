<?php

namespace App\Modules\Common\Domain\Service\Rules\ContextInterfaces;

use App\Modules\Common\Domain\Entity\Tariff;

interface HasTariff
{
    public function getTariff(): Tariff;
}