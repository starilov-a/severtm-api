<?php declare(strict_types=1);

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\Tariff;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\TariffGroup;
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
        $this->getEntityManager()->persist($group);
        $this->getEntityManager()->flush();

        return $group;
    }

    public function linkTariffForGroup(Tariff $tariff, TariffGroup $tariffGroup): void
    {
        $this->_em->getConnection()->insert('tariffs_belong_groups', [
            'tc_id' => $tariff->getId(),
            'tariffs_group_id' => $tariffGroup->getId(),
        ]);
    }
}
