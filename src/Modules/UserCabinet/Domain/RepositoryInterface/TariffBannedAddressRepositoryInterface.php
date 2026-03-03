<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\Address;
use App\Modules\UserCabinet\Domain\Entity\Tariff;
use App\Modules\UserCabinet\Domain\Entity\TariffBannedAddress;

interface TariffBannedAddressRepositoryInterface extends RepositoryInterface
{
    public function isTariffBannedForAddress(Address $address, Tariff $tariff): bool;
    public function isTariffAvailableForAddress(Address $address, Tariff $tariff): bool;
    public function isTariffBannedForAddressId(int $addressId, int $tariffTid): bool;
    public function isTariffAvailableForAddressId(int $addressId, int $tariffTid): bool;
}
