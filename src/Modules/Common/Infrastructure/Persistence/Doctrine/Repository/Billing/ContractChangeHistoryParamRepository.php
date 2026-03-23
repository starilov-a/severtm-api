<?php declare(strict_types=1);

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\ContractChangeHistory;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\ContractChangeHistoryParam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class ContractChangeHistoryParamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContractChangeHistoryParam::class);
    }

    public function save(ContractChangeHistoryParam $param): ContractChangeHistoryParam
    {
        $this->getEntityManager()->persist($param);
        $this->getEntityManager()->flush();

        return $param;
    }

    /**
     * @return ContractChangeHistoryParam[]
     */
    public function findByHistory(ContractChangeHistory $history): array
    {
        return $this->findBy(['history' => $history]);
    }
}
