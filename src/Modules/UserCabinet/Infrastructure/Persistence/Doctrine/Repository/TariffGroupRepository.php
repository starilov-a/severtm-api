<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\UserCabinet\Domain\RepositoryInterface\TariffGroupRepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\Tariff;
use App\Modules\UserCabinet\Domain\Entity\TariffGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TariffGroupRepository extends ServiceEntityRepository implements TariffGroupRepositoryInterface
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
