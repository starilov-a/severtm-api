<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\FinPeriod;
use App\Modules\Common\Domain\Entity\Tariff;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\UserServMode;
use App\Modules\Common\Domain\Repository\FinPeriodRepository;
use App\Modules\Common\Domain\Repository\TariffRepository;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Repository\UserServModeRepository;
use App\Modules\Common\Domain\Repository\WebActionRepository;
use App\Modules\Common\Domain\Service\Dto\Request\TariffFilterDto;
use App\Modules\Common\Domain\Service\Rules\Chains\Tariff\ChangeTariffRuleChain;
use App\Modules\Common\Domain\Service\Rules\Contexts\ChangeTariffContext;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;

class TariffService
{
    public function __construct(
        protected TariffRepository $tariffRepo,
        protected FinPeriodRepository $finPeriodRepo,
        protected UserServModeRepository $userServModeRepo,
        protected UserRepository $userRepo,
        protected WebActionRepository $webActionRepo,

        protected UserService $userService,
        protected UserServModeService $userServModeService,

        protected ChangeTariffRuleChain $changeTariffRuleChain,
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
        ));

        // запись в таблице users
        $isCurrentMonth ? $user->setCurrentTariff($newTariff) : $user->setNextTariff($newTariff);

        // выставление скорости у пользователя в таблице users
        $user->setBw($newTariff->getMaxBw());
        $user->setCurrentBw($newTariff->getBw());

        $this->userService->save($user);

        return true;
    }

    public function getTariffsForClient(User $user, TariffFilterDto $dto = new TariffFilterDto()): array
    {
        //1. Тарифы активные
        $dto->setActiveStatus(true);

        //2. Тариф имеет группу, обозначающую необходимый регион
        $userRegion = $user->getRegion();
        array_map(function ($region) use ($dto) {
            $dto->addRegionGroupCodes($region);
        }, [
            1 => 'velikij_novgorod_tariffs',
            2 => 'cherepevets_tariffs',
            3 => 'chelyzbinsk_tariffs',
            4 => 'yaroslavl_tariffs'
        ]);

        return $this->tariffRepo->getTariffs($dto);
    }

    public function disableTariffByUserServMode(UserServMode $userServMode): UserServMode
    {
        $userServMode->setIsActive(false);
        return $this->userServModeService->save($userServMode);

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
        $this->userServModeService->save($userServMode);

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
            $this->userServModeService->save($userServMode);
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
