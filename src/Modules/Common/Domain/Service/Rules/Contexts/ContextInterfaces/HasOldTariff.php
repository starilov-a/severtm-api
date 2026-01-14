<?php

namespace App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces;

use App\Modules\Common\Domain\Entity\Tariff;

interface HasOldTariff
{
    public function getOldTariff(): Tariff;
}