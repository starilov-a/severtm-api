<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\FinPeriod;
use App\Modules\Common\Domain\Entity\ProdServMode;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Repository\FinPeriodRepository;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Repository\UserServModeRepository;
use App\Modules\Common\Domain\Repository\WebActionRepository;
use App\Modules\Common\Domain\Service\Dto\Request\AddServiceOptionsDto;
use App\Modules\Common\Domain\Service\Dto\Request\CreateUserServModeDto;
use App\Modules\Common\Domain\Service\Dto\Request\OptionsUserServModeDto;
use App\Modules\Common\Domain\Service\Dto\Request\UserServModeDto;
use App\Modules\Common\Domain\Service\Rules\ProdServModes\FinPeriodMustBeIssetContext;
use App\Modules\Common\Domain\Service\Rules\ProdServModes\FinPeriodMustBeIssetRule;
use App\Modules\Common\Domain\Service\Rules\ProdServModes\UnitsMustBePositiveContext;
use App\Modules\Common\Domain\Service\Rules\ProdServModes\UnitsMustBePositiveRule;
use App\Modules\Common\Infrastructure\Exception\ImportantBusinessException;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;
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

        protected UnitsMustBePositiveRule $unitsMustBePositiveRule,
        protected FinPeriodMustBeIssetRule $finPeriodMustBeIssetRule,
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
            $service = $mode->getMode()->getService();
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

    /*
     * Добавление нового режима услуги клиенту
     * */
    protected function addServiceMode(User $user, ProdServMode $mode, OptionsUserServModeDto $optionsUserServModeDto): bool
    {
        $webAction = $this->webActionRepo->findIdByCid('WA_USERS_ADD_SERVICES');
        $master = $this->userRepo->find(UserSessionService::getUserId());

        // Логика:
        // 1. TODO: Есть ли права на добавление услуги
        // 2. Кол-во услуг больше 1 (по умолчанию в $options)
        $this->unitsMustBePositiveRule->check(
            new UnitsMustBePositiveContext(
                $master->getId(),
                $webAction->getId(),
                $optionsUserServModeDto->getCountUnits()
            )
        );

        // 3. Проверка пользователя - сложная логика

        // 3.1 Регион услуги и регион пользователя совпадает
        // 3.2 Совпадает юр признак пользователя и услуги

        // 4. режим не относится к комплексным
        $this->finPeriodMustBeIssetRule->check(new FinPeriodMustBeIssetContext(
            $master->getId(),
            $webAction->getId(),
            $optionsUserServModeDto->getCountUnits()
        ));

        //TODO: Комплексные тарифы не актуальны. Возможно стоит убрать эту проверку
        if ($mode->isComplex() || true)
            throw new ImportantBusinessException($master->getId(), $webAction->getId(),'Режим не должен относится к комплексным');

        // Добавление услуги в транзакции
        return $this->em->getConnection()->transactional(function () use (
            $user,
            $master,
            $mode,
            $webAction,
            $optionsUserServModeDto
        ) {
            // 1. Добавление устройства если есть
            if (!empty($optionsUserServModeDto->getDevice))
                $this->deviceService->addForUser($user, $optionsUserServModeDto->getDevice());

            // 2. Добавление usm
            $userServMode = $this->userServModeRepo->createUserServMode($user, $optionsUserServModeDto);

            // 3. Списание деняг
            $this->writeOffService->makeWriteOffForMode(
                $user,
                $userServMode,
                $optionsUserServModeDto->getFinPeriod()
            );
            // 4. Логирование
            $comment = $optionsUserServModeDto->getComment();
            $this->loggerService->businessLog(new BusinessLogDto(
                $master->getId(),
                $webAction->getId(),
                'Услуга('.$mode->getName().'-'.$mode->getId().' ) добавлена пользователю ' . $user->getId() .
                '. Кол-во услуг - ' . $optionsUserServModeDto->getCountUnits(). '. ' . (!empty($comment) ? 'Комментарий ('.$comment.')' : '' ) ,
                true
            ));
        });
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

    // Использовать для добавления услуги в след. месяце
    public function addNextServiceMode(
        User $user,
        ProdServMode $mode,
        OptionsUserServModeDto $optionsUserServModeDto = new OptionsUserServModeDto
    ): void
    {
        $optionsUserServModeDto->setFinPeriod($this->finPeriodRepo->getNext());
        $this->addServiceMode($user, $mode, $optionsUserServModeDto);
    }
}