<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Domain\Repository;

use App\Modules\UserCabinet\Domain\Entity\TariffGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TariffGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TariffGroup::class);
    }

    public function save(TariffGroup $group): TariffGroup
    {
        $this->_em->persist($group);
        $this->_em->flush();

        return $group;
    }
}
