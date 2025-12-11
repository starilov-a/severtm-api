<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\Device;
use App\Modules\Common\Domain\Entity\FinPeriod;
use App\Modules\Common\Domain\Entity\ProdServMode;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\UserServMode;
use App\Modules\Common\Domain\Repository\FinPeriodRepository;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Repository\UserServModeRepository;
use App\Modules\Common\Domain\Repository\WebActionRepository;
use App\Modules\Common\Domain\Service\Dto\Request\OptionsUserServModeDto;
use App\Modules\Common\Domain\Service\Dto\Request\TypedWriteOffDto;
use App\Modules\Common\Domain\Service\Rules\Chains\AddServiceModeContext;
use App\Modules\Common\Domain\Service\Rules\Chains\AddServiceModeRuleChain;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;
use App\Modules\UserCabinet\Service\Dto\Response\WriteOffDto;
use Doctrine\ORM\EntityManagerInterface;

class UserServModeService
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected UserServModeRepository $userServModeRepo,
        protected DeviceService $deviceService,
        protected WriteOffService $writeOffService,
        protected LoggerService $loggerService,
        protected WebActionRepository $webActionRepo,
        protected UserRepository $userRepo,
        protected FinPeriodRepository $finPeriodRepo,

        protected AddServiceModeRuleChain $addServiceModeRuleChain,
    ){}

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
            $service = $mode->getProductService();
            $service->addMode($mode);

            $currentServs[$service->getId()] = $service;
        }

        return $currentServs;
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

    // Использовать для добавления услуги в текущем месяце
    public function addCurrentServiceMode(
        User $user,
        ProdServMode $mode,
        OptionsUserServModeDto $optionsUserServModeDto = new OptionsUserServModeDto
    ): void
    {
        $optionsUserServModeDto->setFinPeriod($this->finPeriodRepo->getCurrent());
        $this->addServiceMode($user, $mode, $optionsUserServModeDto);
    }

    // Использовать для добавления услуги в следующем месяце
    public function addNextServiceMode(
        User $user,
        ProdServMode $mode,
        OptionsUserServModeDto $optionsUserServModeDto = new OptionsUserServModeDto
    ): void
    {
        $optionsUserServModeDto->setFinPeriod($this->finPeriodRepo->getNext());
        $this->addServiceMode($user, $mode, $optionsUserServModeDto);
    }

    /*
     * Добавление нового режима услуги клиенту
     * */
    protected function addServiceMode(User $user, ProdServMode $mode, OptionsUserServModeDto $optionsUserServModeDto): void
    {
        $webAction = $this->webActionRepo->findIdByCid('WA_USERS_ADD_SERVICES');
        $master = $this->userRepo->find(UserSessionService::getUserId());

        // Цепочка проверок
        $this->addServiceModeRuleChain->checkAll(new AddServiceModeContext(
            userId: $master->getId(),
            actionId: $webAction->getId(),
            finPeriod: $optionsUserServModeDto->getFinPeriod(),
            mode: $mode,
            jurStatus: $user->isJuridical(),
            region: $user->getRegion(),
            modeUnitCount: $optionsUserServModeDto->getCountUnits(),
        ));

        // Добавление услуги в транзакции
        $this->em->getConnection()->transactional(function () use (
            $user,
            $master,
            $mode,
            $webAction,
            $optionsUserServModeDto
        ) {
            // 1. Добавление usm
            $userServMode = $this->createUserServMode($user, $mode, $optionsUserServModeDto);

            // 2. Добавление устройства и привязка к текущей услуге
            if (!empty($optionsUserServModeDto->getDeviceDto())) {
                $device = $this->deviceService->addOrCreateForUser($user, $optionsUserServModeDto->getDeviceDto());
                $this->deviceService->attachDeviceToServiceMode($userServMode, $device);
            }

            // 3.1 Подготовка объекта для создания списания
            $writeOffDto = new TypedWriteOffDto();
            $writeOffDto->setServMode($userServMode);
            $writeOffDto->setPayableType('no_packet');
            $writeOffDto->setComment('Добавление услуги ' . $userServMode->getMode()->getName());
            $writeOffDto->setUser($user);
            // 3.2 Списание деняг
            $this->writeOffService->makeWriteOffForAddingMode($writeOffDto);

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
        });
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

    // Конечное применение userServMode
    public function save(UserServMode $userServMode): UserServMode
    {
        $this->em->persist($userServMode);
        $this->em->flush();

        return $userServMode;
    }
}