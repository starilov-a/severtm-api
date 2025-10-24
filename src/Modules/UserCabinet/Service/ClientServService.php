<?php

namespace App\Modules\UserCabinet\Service;

use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;
use App\Modules\UserCabinet\Repository\TariffRepository;
use App\Modules\UserCabinet\Repository\UserRepository;
use App\Modules\UserCabinet\Repository\WebActionRepository;

class ClientServService
{
    protected UserRepository $userRepo;
    protected ServModeService $servModeService;
    protected ServService $servService;
    public function __construct(
        UserRepository $userRepository,
        ServModeService $servModeService,
        ServService $servService
    )
    {
        $this->userRepo = $userRepository;
        $this->servModeService = $servModeService;
        $this->servService = $servService;
    }
    /*
     * Список доступных услуг на подключение клиенту
     * */
    public function listAvailableServices(int $uid): array
    {
        $user = $this->userRepo->find($uid);
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

        return array_map(function ($serv) use($servModeFilterDto) {
            return [
                'servId' => $serv->getId(),
                'name' => $serv->getName(),
                'code' =>$serv->getStrCode(),
                'modes' => array_map(function ($mode) {
                    return [
                        'modeId' => $mode->getId(),
                        'name' => $mode->getName(),
                        'code' =>$mode->getStrCode()
                    ];
                },  $this->servModeService->getActiveModes($serv, $servModeFilterDto)),
            ];
        }, $servs);
    }

    /*
     * Получение активных услуг клиента
     * */
    public function getCurrentServices(int $uid): array
    {
        return [];
    }

    /*
     * Активация услуги клиента
     * */
    public function enableService(int $uid, int $modeId): bool
    {

        return false;
    }

    /*
     * Отключение услуги клиента
     * */
    public function disableService(int $uid, int $serviceId): bool
    {
        return false;
    }

    /*
     * Заморозка услуг клиента
     * */
    public function freezeServices(int $uid): bool
    {
        return false;
    }

    /*
     * Получение отсрочки для клиента
     * */
    public function takeBreak(int $uid): bool
    {
        return false;
    }
}