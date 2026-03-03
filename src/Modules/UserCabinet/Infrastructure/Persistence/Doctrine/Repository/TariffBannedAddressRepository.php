<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\UserCabinet\Domain\RepositoryInterface\TariffBannedAddressRepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\Address;
use App\Modules\UserCabinet\Domain\Entity\Tariff;
use App\Modules\UserCabinet\Domain\Entity\TariffBannedAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ParameterType;
use Doctrine\Persistence\ManagerRegistry;

final class TariffBannedAddressRepository extends ServiceEntityRepository implements TariffBannedAddressRepositoryInterface
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

