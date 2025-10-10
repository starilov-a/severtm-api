<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Repository;

use App\Modules\UserCabinet\Entity\Region;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class RegionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Region::class);
    }

    public function findByStrCode(string $strCode): ?Region
    {
        return $this->findOneBy(['strCode' => $strCode]);
    }
}
