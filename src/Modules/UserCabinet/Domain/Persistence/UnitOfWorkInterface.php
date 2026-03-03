<?php

namespace App\Modules\UserCabinet\Domain\Persistence;

interface UnitOfWorkInterface
{
    public function persist(object $entity): void;
    public function remove(object $entity): void;
    public function flush(): void;
}
