<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\WebHistory;

interface WebHistoryRepositoryInterface extends RepositoryInterface
{
    public function log(int $userId, int $actionId, string $message, int $result = 0, ?string $ip = null, ?\DateTimeImmutable $when = null): int;
    public function logByCid(int $userId, string $cid, string $message, int $result = 0, ?string $ip = null, ?\DateTimeImmutable $when = null): int;
    public function recentForUser(int $userId, int $limit = 50): array;
    public function findByFilters(?int $userId, ?string $cid, ?\DateTimeInterface $from, ?\DateTimeInterface $to, int $limit = 100, int $offset = 0): array;
}
