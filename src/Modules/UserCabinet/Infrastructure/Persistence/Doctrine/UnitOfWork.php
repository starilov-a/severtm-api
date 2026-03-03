<?php

namespace App\Modules\UserCabinet\Infrastructure\Persistence\Doctrine;

use App\Modules\UserCabinet\Domain\Persistence\UnitOfWorkInterface;
use Doctrine\ORM\EntityManagerInterface;

class UnitOfWork implements UnitOfWorkInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    public function persist(object $entity): void
    {
        $this->em->persist($entity);
    }

    public function remove(object $entity): void
    {
        $this->em->remove($entity);
    }

    public function flush(): void
    {
        $this->em->flush();
    }
}
