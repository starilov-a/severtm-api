<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\Tariff;
use App\Modules\UserCabinet\Domain\Entity\TariffGroup;

interface TariffGroupRepositoryInterface extends RepositoryInterface
{
    public function save(TariffGroup $group): TariffGroup;
    public function linkTariffForGroup(Tariff $tariff, TariffGroup $tariffGroup): void;
}
