<?php

namespace App\Modules\UserCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\UserCabinet\Domain\RepositoryInterface\AddressRepositoryInterface;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\Region;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AddressRepository extends ServiceEntityRepository implements AddressRepositoryInterface
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Region::class);
    }

    public function getRegionForAddressId(int $addressId): ?Region
    {
        $sql = <<<SQL
            SELECT r.region_id
            FROM addresses a
            JOIN districts d ON d.district_id = a.address_district
            JOIN regions r   ON r.region_id = d.region_id
            WHERE a.address_id = :id
            LIMIT 1
        SQL;

        $rid = $this->getEntityManager()->getConnection()->fetchOne($sql, ['id' => $addressId]);
        return $rid ? $this->getEntityManager()->find(Region::class, (int)$rid) : null;
    }
}