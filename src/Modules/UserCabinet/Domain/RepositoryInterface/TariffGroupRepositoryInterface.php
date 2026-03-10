<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\Tariff;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\TariffGroup;

interface TariffGroupRepositoryInterface extends RepositoryInterface
{
    public function save(TariffGroup $group): TariffGroup;
    public function linkTariffForGroup(Tariff $tariff, TariffGroup $tariffGroup): void;
}
