<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Repository\UserServModeRepository;
use App\Modules\UserCabinet\Service\Dto;

class UserServService
{
    protected UserRepository $userRepo;
    protected ServModeService $servModeService;
    protected ServService $servService;
    protected UserServModeService $userServModeService;
    public function __construct(
        UserRepository $userRepository,
        UserServModeRepository $userSer,
        ServModeService $servModeService,
        ServService $servService,
        UserServModeService $userServModeService
    )
    {
        $this->userRepo = $userRepository;
        $this->servModeService = $servModeService;
        $this->servService = $servService;
        $this->userServModeService = $userServModeService;
    }
    /*
     * Список доступных услуг на подключение клиентом
     * */
    public function listAvailableServicesWithModes(): array
    {
        $clientGroup = 'canBeChangeByClient';

        #1. Получение данных услуг
        $serviceFilterDto = new Dto\Request\ServiceFilterDto();
        //2. Услуги имею группы "canBeChangeByClient"
        $serviceFilterDto->addGroupCode($clientGroup);
        //3. Регион

        $servs = $this->servService->getActiveServs($serviceFilterDto);

        #2. Получение режимов услуг
        $servModeFilterDto = new Dto\Request\ServModeFilterDto();

        //1. Наличие группы
        $servModeFilterDto->addGroupCode($clientGroup);

        // Наполнение serv и modes
        foreach ($servs as $serv) {
            $servModeFilterDto->setServService($serv);
            $modes = $this->servModeService->getActiveModes($servModeFilterDto);
            foreach ($modes as $mode)
                $serv->addMode($mode);
        }
        return $servs;
    }

    /*
     * Получение активных услуг клиента
     * */
    public function getCurrentServicesWithModes(User $user): array
    {
        $servs = $this->userServModeService->getCurrentModesWithService($user);

        return $servs;
    }
}