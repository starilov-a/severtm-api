<?php

namespace App\Modules\JurManagerCabinet\Infrastructure\Persistence\Doctrine\Repository\Mappers;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\Address;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;
use App\Modules\JurManagerCabinet\Domain\Entity\Contract\Contract;
use App\Modules\JurManagerCabinet\Domain\Entity\Contract\ContractStatus;

class ContractMapper
{
    static public function map(User $tableUser): Contract
    {
        $tableAddress = $tableUser->getAddress();

        $DomainAddress = new \App\Modules\JurManagerCabinet\Domain\Entity\Address(
            $tableAddress->getId(),
            $tableAddress->getName(),
            $tableAddress->getDistrict()->getId(),
            $tableAddress->getDistrict()->getName()
        );

        return new Contract(
            $tableUser->getId(),
            $tableUser->getCustomerInn()?->getInn() ?? '',
            $tableUser->isDeleted() ? ContractStatus::BLOCKED : ContractStatus::UNBLOCKED,
            $tableUser->getFullName(),
            $tableUser->getPassport(),
            $tableUser->getLogin(),
            (string) $tableUser->getEmail(),
            (string) $tableUser->getPhoneExtra(),
            (bool) $tableUser->isDeleted(),
            $DomainAddress
        );
    }
}