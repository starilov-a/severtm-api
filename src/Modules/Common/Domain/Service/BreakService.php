<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Repository\WebActionRepository;

class BreakService
{
    public function __construct(
        protected WebActionRepository $webActionRepo,
    ) {}
    public function getCountBreaksForUser(User $user): int
    {
        $webAction = $this->webActionRepo->findIdByCid('WA_USERS_GIVECREDIT'); //WA_USERS_GIVECREDIT_4DAY

        return 0;
    }

    public function getBreakStatus(User $users): array
    {
        return [];
    }
}