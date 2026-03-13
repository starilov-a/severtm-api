<?php

namespace App\Modules\UserCabinet\Application\UseCase\Payment;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\ProdServModeCost;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserServMode;
use App\Modules\UserCabinet\Domain\RepositoryInterface\ProdServModeCostRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserServModeRepositoryInterface;

class GetCurrentPaymentUseCase
{

    public function __construct(
        protected UserRepositoryInterface $userRepo,
        protected UserServModeRepositoryInterface $userServModeRepo,
        protected ProdServModeCostRepositoryInterface $prodServModeCostRepo
    ) {}
    /*
     * Получение текущей абонентской платы
     * */
    public function handle(int $uid): int
    {
        /* @var User $user */
        $user = $this->userRepo->find($uid);

        /* @var UserServMode[] $activeServModes */
        $activeServModes = $this->userServModeRepo->findCurrentActiveModes($user);

        $currentPayment = 0;
        foreach ($activeServModes as $activeServMode) {
            $countServ = $activeServMode->getUnits();
            $costServ = $this->prodServModeCostRepo->findOneBy(['mode' => $activeServMode->getMode()])->getCost();

            $currentPayment += $countServ * $costServ;
        }

        return $currentPayment;
    }
}