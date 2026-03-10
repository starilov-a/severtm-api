<?php

namespace App\Modules\UserCabinet\Domain\Service;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\BlockState;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;
use App\Modules\UserCabinet\Domain\RepositoryInterface\FinPeriodRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\TariffRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface           $repo,
        protected FinPeriodRepositoryInterface      $finPeriodRepo,
        protected TariffRepositoryInterface         $tariffRepo,
    ) {}

    /**
     * Обновление даты учетного периода на 1 месяц
     *
     * @param $user
     * @return User
     */
    public function updatingAbPeriod(User $user): User
    {
        $startDate = $this->finPeriodRepo->getCurrent()->getStartDate();
        $user->setAbPstart($startDate);
        $user->setAbPend($startDate->modify('+1 month'));

        return $this->repo->save($user);
    }

    /**
     * Обновление статуса блокировки
     *
     * @param $user
     * @return User
     */
    public function updatingBlockState(User $user, BlockState $blockState): User
    {
        $user->setBlockState($blockState);
        return $this->repo->save($user);
    }

    public function disconnectCurrentTariff(User $user): User
    {
        $disconnectedTariff = $this->tariffRepo->find(1);
        $user->setCurrentTariff($disconnectedTariff);

        return $this->repo->save($user);
    }
}
