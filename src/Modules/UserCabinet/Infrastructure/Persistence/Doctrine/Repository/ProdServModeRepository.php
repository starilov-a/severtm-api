<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\UserCabinet\Domain\Dto\Request\ServModeFilterDto;
use App\Modules\UserCabinet\Domain\Entity\ProdServMode;
use App\Modules\UserCabinet\Domain\RepositoryInterface\ProdServModeRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProdServModeRepository extends ServiceEntityRepository implements ProdServModeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProdServMode::class);
    }

    public function getModeByFilter(ServModeFilterDto $filter): array
    {
        $qb = $this->createQueryBuilder('psm');

        $serv = $filter->getProductService();
        if (isset($serv))
            $qb->andWhere('psm.service = :service')->setParameter('service', $serv);



        //TODO: сделать как свяжем группы
//        $codes = $filter->getGroupCodes();
//        if (!empty($codes))
//            $qb->join('m.groups', 'g')->andWhere('g.code IN (:codes)')->setParameter('codes', implode(',',$codes));

        $qb->orderBy('psm.'.$filter->getOrderBy(), $filter->getOrderDir());

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

    public function hasGroup(int $prodServModeId, string $groupString): bool
    {
        // Определяем jur status по наличию группы
        $sql = <<<SQL
        SELECT * FROM psm_belong_groups pbg
            JOIN psm_groups pg ON pg.psm_group_id = pbg.psm_group_id AND pg.psm_grp_code = :grpstr
            WHERE pbg.srvmode_id = :psmid
            LIMIT 1;
        SQL;

        return false !== $this->getEntityManager()->getConnection()->fetchOne($sql, [
                'grpstr' => $groupString,
                'psmid' => $prodServModeId
            ]);
    }

    public function isAvailableForRegionByCode(int $prodServModeId, string $regionCode): bool
    {
        // TODO: сделать связь между группой и городом - в БД

        $sql = <<<SQL
        SELECT * FROM psm_belong_groups pbg
        JOIN psm_groups pg ON pg.psm_group_id = pbg.psm_group_id
        WHERE pbg.srvmode_id = :psmi AND pg.psm_grp_code = :grpstr 
        LIMIT 1;
        SQL;

        return false !== $this->getEntityManager()->getConnection()->fetchOne($sql, [
                'psmi' => $prodServModeId,
                'grpstr' => $regionCode,
            ]);
    }
}
