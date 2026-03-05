<?php declare(strict_types=1);

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\BlockState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class BlockStateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlockState::class);
    }

    public function findByCode(string $code): ?BlockState
    {
        return $this->findOneBy(['code' => $code]);
    }
}
