<?php

namespace App\Modules\UserCabinet\Domain\Service;

use App\Modules\UserCabinet\Domain\Contexts\Definitions\Tariff\ChangeTariffContext;
use App\Modules\UserCabinet\Domain\Dto\Request\TariffFilterDto;
use App\Modules\UserCabinet\Domain\Entity\FinPeriod;
use App\Modules\UserCabinet\Domain\Entity\Tariff;
use App\Modules\UserCabinet\Domain\Entity\User;
use App\Modules\UserCabinet\Domain\Entity\UserServMode;
use App\Modules\UserCabinet\Domain\RepositoryInterface\FinPeriodRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\TariffRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserServModeRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\WebActionRepositoryInterface;
use App\Modules\UserCabinet\Domain\Rules\Chains\Tariff\ChangeTariffRuleChain;
use App\Modules\UserCabinet\Infrastructure\Service\Auth\Service\UserSessionService;

class TariffService
{
    public function __construct(
        protected TariffRepositoryInterface          $tariffRepo,
        protected FinPeriodRepositoryInterface       $finPeriodRepo,
        protected UserServModeRepositoryInterface    $userServModeRepo,
        protected UserRepositoryInterface            $userRepo,
        protected WebActionRepositoryInterface       $webActionRepo,

        protected UserService               $userService,
        protected UserServModeService       $userServModeService,
        protected TariffGroupService        $tariffGroupService,

        protected ChangeTariffRuleChain     $changeTariffRuleChain,
    ) {}

    /**
     * Изменение только текущего или след. тарифа для пользователя.
     * Использовать без агрегатора нельзя
     *
     * @param User $user
     * @param Tariff $newTariff
     * @param FinPeriod $finPeriod
     * @return bool
     * @throws \Exception
     */
    public function changeUserTariff(User $user, Tariff $newTariff, FinPeriod $finPeriod): bool
    {
        $isCurrentMonth = $finPeriod->isCurrent();

        $oldTariff = $isCurrentMonth ? $user->getCurrentTariff() : $user->getNextTariff();
        $master = $this->userRepo->find(UserSessionService::getUserId());
        $webAction = $this->webActionRepo->findIdByCid('WA_USERS_CHANGE_TARIFFS');

        $this->changeTariffRuleChain->checkAll(new ChangeTariffContext(
            $webAction,
            $master,
            $user,
            $newTariff,
            $oldTariff,
            $user->getRegion()
        ));

        // запись в таблице users
        $isCurrentMonth ? $user->setCurrentTariff($newTariff) : $user->setNextTariff($newTariff);

        // выставление скорости у пользователя в таблице users
        $user->setBw($newTariff->getMaxBw());
        $user->setCurrentBw($newTariff->getBw());

        $this->userRepo->save($user);

        return true;
    }

    public function getTariffsByUser(User $user, TariffFilterDto $dto = new TariffFilterDto()): array
    {
        //1. Тарифы активные
        $dto->setActiveStatus(true);

        //2. Тариф имеет группу, обозначающую необходимый регион
        $userRegion = $user->getRegion();
        $regions = $this->tariffGroupService->getTariffGroupRegions();
        $dto->addRegionGroupCodes($regions[$userRegion->getId()]);

        //3. Тарифы по пользователю
        return $this->tariffRepo->getTariffs($dto);
    }

    public function disableTariffByUserServMode(UserServMode $userServMode): UserServMode
    {
        $userServMode->setIsActive(false);

        // выставление скорости у пользователя в таблице users
        $user = $userServMode->getUser();
        $disableTariff = $this->tariffRepo->find(1);
        $user->setCurrentTariff($disableTariff);
        $user->setBw($disableTariff->getMaxBw());
        $user->setCurrentBw($disableTariff->getBw());

        $this->userRepo->save($user);

        return $this->userServModeRepo->save($userServMode);

    }

    public function disableCurrentUserTariff(User $user): bool
    {
        $prodServMode = $user->getCurrentTariff()->getProdServMode();
        $userServMode= $this->userServModeRepo->findOneBy([
            'user' => $user,
            'mode' => $prodServMode,
            'finPeriod' => $this->finPeriodRepo->getCurrent()
        ]);

        $userServMode->setIsActive(false);
        $this->userServModeRepo->save($userServMode);

        return true;
    }

    public function removeNextUserTariff(User $user): bool
    {
        $prodServMode = $user->getNextTariff()->getProdServMode();
        $userServMode= $this->userServModeRepo->findOneBy([
            'user' => $user,
            'mode' => $prodServMode,
            'finPeriod' => $this->finPeriodRepo->getNext()
        ]);

        if ($userServMode) {
            $userServMode->setIsActive(false);
            $this->userServModeRepo->save($userServMode);
        }

        return true;
    }

    public function getActiveUserServModesByUser(User $user): ?array
    {
        return $this->userServModeRepo->findActiveTariffsByUser($user);
    }

    public function getLastActiveUserServModesByUser(User $user): UserServMode
    {
        return $this->userServModeRepo->findActiveTariffsByUserAndFinPeriod(
            $user,
            $this->finPeriodRepo->findOneBy(['startDate' => new \DateTimeImmutable($user->getBlockDate()->format('Y-m-01 00:00:00'))])
        );
    }
}
