<?php declare(strict_types=1);

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\ProdServMode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProdServModeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProdServMode::class);
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
