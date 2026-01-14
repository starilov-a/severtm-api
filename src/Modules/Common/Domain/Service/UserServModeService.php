<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\ProdServMode;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\UserServMode;
use App\Modules\Common\Domain\Repository\FinPeriodRepository;
use App\Modules\Common\Domain\Repository\UserJurStateRepository;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Repository\UserServModeRepository;
use App\Modules\Common\Domain\Repository\WebActionRepository;
use App\Modules\Common\Domain\Service\Dto\Request\OptionsUserServModeDto;
use App\Modules\Common\Domain\Service\Rules\Chains\UserServMode\AddServiceModeRuleChain;
use App\Modules\Common\Domain\Service\Rules\Contexts\AddServiceModeContext;
use App\Modules\Common\Domain\Service\Rules\Definitions\User\UserIsNotNotActivatedRule;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;
use Doctrine\ORM\EntityManagerInterface;

class UserServModeService
{
    public function __construct(
        protected EntityManagerInterface    $em,
        protected UserServModeRepository    $userServModeRepo,
        protected DeviceService             $deviceService,
        protected LoggerService             $loggerService,
        protected WebActionRepository       $webActionRepo,
        protected UserRepository            $userRepo,
        protected FinPeriodRepository       $finPeriodRepo,
        protected UserOwnDeviceService      $userOwnDeviceService,
        protected UserJurStateRepository    $jurStateRepo,
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
        $this->save($userServMode);

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

        return $this->save($userServMode);
    }

    public function delete(ProdServMode $prodServMode): bool
    {
        $this->em->remove($prodServMode);
        $this->em->flush();

        return true;
    }

    // Конечное применение userServMode
    public function save(UserServMode $userServMode): UserServMode
    {
        $this->em->persist($userServMode);
        $this->em->flush();

        return $userServMode;
    }
}