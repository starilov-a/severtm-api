<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\User;

interface DebtRepositoryInterface extends RepositoryInterface
{
    public function sumByUser(User $user): float;
}
