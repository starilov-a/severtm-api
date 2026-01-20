<?php

namespace App\Modules\Common\Domain\Service;

class TariffGroupService
{
    public function getTariffGroupRegions(): array
    {
        return [
            1 => 'velikij_novgorod_tariffs',
            2 => 'cherepevets_tariffs',
            3 => 'chelyzbinsk_tariffs',
            4 => 'yaroslavl_tariffs',
            200 => 'yaroslavl_tariffs'
        ];
    }
}