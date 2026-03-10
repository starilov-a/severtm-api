<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\Address;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\Tariff;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\TariffBannedAddress;

interface TariffBannedAddressRepositoryInterface extends RepositoryInterface
{
    public function isTariffBannedForAddress(Address $address, Tariff $tariff): bool;
    public function isTariffAvailableForAddress(Address $address, Tariff $tariff): bool;
    public function isTariffBannedForAddressId(int $addressId, int $tariffTid): bool;
    public function isTariffAvailableForAddressId(int $addressId, int $tariffTid): bool;
}
