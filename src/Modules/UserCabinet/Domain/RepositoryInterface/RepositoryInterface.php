<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

interface RepositoryInterface
{
    public function find(mixed $id): ?object;
    public function findOneBy(array $criteria, ?array $orderBy = null): ?object;
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array;
    public function findAll(): array;
}
