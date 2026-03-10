<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;

interface DebtRepositoryInterface extends RepositoryInterface
{
    public function sumByUser(User $user): float;
}
