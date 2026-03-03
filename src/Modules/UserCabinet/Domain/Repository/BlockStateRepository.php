<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Domain\Repository;

use App\Modules\UserCabinet\Domain\Entity\BlockState;
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
