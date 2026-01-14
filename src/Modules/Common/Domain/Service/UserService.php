<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\BlockState;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Repository\FinPeriodRepository;
use App\Modules\Common\Domain\Repository\TariffRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    public function __construct(
        protected EntityManagerInterface    $em,

        protected FinPeriodRepository       $finPeriodRepo,
        protected TariffRepository          $tariffRepo,
    ) {}

    public function save(User $user): User
    {
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

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

        $this->save($user);
        return $this->save($user);
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
        $this->save($user);
        return $this->save($user);
    }

    public function disconnectedTariffs(User $user): User
    {
        $disconnectedTariff = $this->tariffRepo->find(1);
        $user->setCurrentTariff($disconnectedTariff);
        $user->setNextTariff($disconnectedTariff);

        return $this->save($user);
    }
}