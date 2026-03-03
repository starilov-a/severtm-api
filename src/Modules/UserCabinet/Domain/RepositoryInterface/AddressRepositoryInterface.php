<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\Region;

interface AddressRepositoryInterface extends RepositoryInterface
{
    public function getRegionForAddressId(int $addressId): ?Region;
}
