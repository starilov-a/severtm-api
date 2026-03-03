<?php

namespace App\Modules\UserCabinet\Domain\Service;

use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserServModeRepositoryInterface;
use App\Modules\UserCabinet\Domain\Service\Dto\Request\ServiceFilterDto;
use App\Modules\UserCabinet\Domain\Service\Dto\Request\ServModeFilterDto;
use App\Modules\UserCabinet\Service\Dto;

class UserServService
{
    protected UserRepositoryInterface $userRepo;
    protected ServModeService $servModeService;
    protected ServService $servService;
    protected UserServModeService $userServModeService;
    public function __construct(
        UserRepositoryInterface $userRepository,
        UserServModeRepositoryInterface $userSer,
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
        $serviceFilterDto = new ServiceFilterDto();
        //2. Услуги имею группы "canBeChangeByClient"
        $serviceFilterDto->addGroupCode($clientGroup);
        //3. Регион
        $servs = $this->servService->getActiveServs($serviceFilterDto);

        #2. Получение режимов услуг
        $servModeFilterDto = new ServModeFilterDto();
        //1. Наличие группы
        $servModeFilterDto->addGroupCode($clientGroup);

        // Наполнение serv и modes
        foreach ($servs as &$serv) {
            $servModeFilterDto->setProductService($serv);
            $modes = $this->servModeService->getActiveModes($servModeFilterDto);
            foreach ($modes as $mode)
                $serv->addMode($mode);
        }
        return $servs;
    }
}