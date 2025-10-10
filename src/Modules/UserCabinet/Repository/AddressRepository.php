<?php

namespace App\Modules\UserCabinet\Repository;

use App\Modules\UserCabinet\Entity\Balance;
use App\Modules\UserCabinet\Entity\Region;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AddressRepository extends ServiceEntityRepository
{

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