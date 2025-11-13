<?php declare(strict_types=1);

namespace App\Modules\Common\Domain\Repository;

use App\Modules\Common\Domain\Entity\ProdServMode;
use App\Modules\Common\Domain\Service\Dto\Request\ServModeFilterDto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class ProdServModeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProdServMode::class);
    }

    public function getModeByFilter(ServModeFilterDto $filter): array
    {
        $qb = $this->createQueryBuilder('m');

        $serv = $filter->getServService();
        if (isset($serv))
            $qb->andWhere('m.service = :service')->setParameter('service', $serv);

        //TODO: сделать как свяжем группы
//        $codes = $filter->getGroupCodes();
//        if (!empty($codes))
//            $qb->join('m.groups', 'g')->andWhere('g.code IN (:codes)')->setParameter('codes', implode(',',$codes));

        $qb->orderBy('m.'.$filter->getOrderBy(), $filter->getOrderDir());

        // Пагинация
        if (null !== $filter->getLimit())  $qb->setMaxResults(max(1, (int)$filter->getLimit()));
        if (null !== $filter->getOffset()) $qb->setFirstResult(max(0, (int)$filter->getOffset()));

        return $qb->getQuery()->getResult();
    }

    /** @return ProdServMode[] */
    public function findVisibleByServiceCode(string $serviceStrCode): array
    {
        return $this->createQueryBuilder('m')
            ->join('m.service', 's')
            ->andWhere('s.strCode = :code')->setParameter('code', $serviceStrCode)
            ->andWhere('m.isVisible = 1')
            ->orderBy('m.priority', 'ASC')
            ->getQuery()->getResult();
    }

    public function findOneByStrCode(string $modeCode): ?ProdServMode
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.strCode = :c')->setParameter('c', $modeCode)
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();
    }
}
