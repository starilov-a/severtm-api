<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\UserPayable;
use App\Modules\Common\Domain\Repository\UserPayableRepository;

class UserPayableService
{
    public function __construct(
        protected UserPayableRepository $userPayableRepo,
    ) {}

    /**
     * Заглушка: создание записи user_payables.
     * Реальную логику/поля нужно будет доделать вместе с mapping сущностей.
     */
    public function createForUser(User $user): UserPayable
    {
        $payable = new UserPayable();

        $em = $this->userPayableRepo->getEntityManager();
        $em->persist($payable);
        $em->flush();

        return $payable;
    }
}

