<?php declare(strict_types=1);

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\Address;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\Tariff;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\TariffBannedAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ParameterType;
use Doctrine\Persistence\ManagerRegistry;

final class TariffBannedAddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TariffBannedAddress::class);
    }

    public function isTariffBannedForAddress(Address $address, Tariff $tariff): bool
    {
        return $this->isTariffBannedForAddressId($address->getId(), $tariff->getTid());
    }

    public function isTariffAvailableForAddress(Address $address, Tariff $tariff): bool
    {
        return !$this->isTariffBannedForAddress($address, $tariff);
    }

    public function isTariffBannedForAddressId(int $addressId, int $tariffTid): bool
    {
        $sql = <<<SQL
            SELECT 1
            FROM tariff_banned_addresses
            WHERE address_id = :addressId
              AND tariff_id = :tariffTid
            LIMIT 1
        SQL;

        $val = $this->getEntityManager()->getConnection()->fetchOne($sql, [
            'addressId' => $addressId,
            'tariffTid' => $tariffTid,
        ], [
            'addressId' => ParameterType::INTEGER,
            'tariffTid' => ParameterType::INTEGER,
        ]);

        return $val !== false;
    }

    public function isTariffAvailableForAddressId(int $addressId, int $tariffTid): bool
    {
        return !$this->isTariffBannedForAddressId($addressId, $tariffTid);
    }
}

