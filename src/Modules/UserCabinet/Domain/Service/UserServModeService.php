<?php

namespace App\Modules\UserCabinet\Domain\Service;

use App\Modules\UserCabinet\Domain\Contexts\Definitions\UserServMode\AddServiceModeContext;
use App\Modules\UserCabinet\Domain\Entity\ProdServMode;
use App\Modules\UserCabinet\Domain\Entity\User;
use App\Modules\UserCabinet\Domain\Entity\UserServMode;
use App\Modules\UserCabinet\Domain\Persistence\UnitOfWorkInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\FinPeriodRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserJurStateRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserServModeRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\WebActionRepositoryInterface;
use App\Modules\UserCabinet\Domain\Rules\Chains\UserServMode\AddServiceModeRuleChain;
use App\Modules\UserCabinet\Domain\Rules\Definitions\User\UserIsNotNotActivatedRule;
use App\Modules\UserCabinet\Domain\Service\Dto\Request\OptionsUserServModeDto;
use App\Modules\UserCabinet\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\UserCabinet\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\UserCabinet\Infrastructure\Service\Logger\LoggerService;

class UserServModeService
{
    public function __construct(
        protected UnitOfWorkInterface       $uow,
        protected UserServModeRepositoryInterface    $userServModeRepo,
        protected DeviceService             $deviceService,
        protected LoggerService             $loggerService,
        protected WebActionRepositoryInterface       $webActionRepo,
        protected UserRepositoryInterface            $userRepo,
        protected FinPeriodRepositoryInterface       $finPeriodRepo,
        protected UserOwnDeviceService      $userOwnDeviceService,
        protected UserJurStateRepositoryInterface    $jurStateRepo,
        protected UserIsNotNotActivatedRule $userIsNotNotActivatedRule,

        protected AddServiceModeRuleChain   $addServiceModeRuleChain,
    ) {}

    /*
     * Получение активных режимов клиента с услугами
     *
     * Группировка по Услугам
     * */
    public function getCurrentServiceWithModes(User $user): array
    {
        $modes = $this->userServModeRepo->findCurrentModesWithService($user);

        //сгруппируем по servs
        $currentServs = [];

        foreach ($modes as $mode) {
            $service = $mode->getMode()->getService();
            $service->addUserMode($mode);

            $currentServs[$service->getId()] = $service;
        }

        return array_values($currentServs);
    }

    /*
     * Получение активных режимов клиента с услугами
     *
     * Без группировки
     * */
    public function getCurrentModesWithService(User $user): array
    {
        return $this->userServModeRepo->findCurrentModesWithService($user);
    }

    /*
     * Добавление нового режима услуги клиенту
     * */
    public function addServiceMode(User $user, ProdServMode $mode, OptionsUserServModeDto $optionsUserServModeDto): UserServMode
    {
        $webAction = $this->webActionRepo->findIdByCid('WA_USERS_ADD_SERVICES');
        $master = $this->userRepo->find(UserSessionService::getUserId());

        // Цепочка проверок
        $this->addServiceModeRuleChain->checkAll(new AddServiceModeContext(
            webAction: $webAction,
            master: $master,
            actionId: $webAction->getId(),
            finPeriod: $optionsUserServModeDto->getFinPeriod(),
            mode: $mode,
            jurStatus: $user->isJuridical(),
            region: $user->getRegion(),
            modeUnitCount: $optionsUserServModeDto->getCountUnits(),
        ));

        // 1. Добавление usm
        $userServMode = $this->createUserServMode($user, $mode, $optionsUserServModeDto);

        // Добавление устройства и привязка к текущей услуге
        if (!empty($optionsUserServModeDto->getDeviceDto())) {
            $device = $this->deviceService->addOrCreateForUser($user, $optionsUserServModeDto->getDeviceDto());
            $userServMode->setDevice($device);
        }

        // фиксируем $userServMode
        $this->userServModeRepo->save($userServMode);

        // 4. Логирование
        $comment = $optionsUserServModeDto->getComment();
        $this->loggerService->businessLog(new BusinessLogDto(
            $master->getId(),
            $webAction->getId(),
            'Услуга('.$mode->getName().':'.$mode->getId().' ) добавлена пользователю ' . $user->getId() .
            '. Кол-во услуг - ' . $optionsUserServModeDto->getCountUnits(). '. ' . (!empty($comment) ? 'Комментарий ('.$comment.')' : '' ) ,
            true
        ));

        return $userServMode;
    }

    protected function createUserServMode(User $user, ProdServMode $prodServMode, OptionsUserServModeDto $optionsUserServModeDto): UserServMode
    {
        $userServMode = new UserServMode();

        $userServMode->setFinPeriod($optionsUserServModeDto->getFinPeriod());
        $userServMode->setUser($user);
        $userServMode->setMode($prodServMode);
        $userServMode->setUnits($optionsUserServModeDto->getCountUnits());
        $userServMode->setIsActive(true);
        $userServMode->setUseCost(true);

        return $this->userServModeRepo->save($userServMode);
    }
}
