<?php

namespace App\Modules\UserCabinet\Service;

use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Service\UserServService;

class LkClientServService
{

    public function __construct(
        protected UserServService $clientServService,
        protected UserRepository  $userRepo
    ){}

    public function listAvailableServices(): array
    {
        $services = $this->clientServService->listAvailableServicesWithModes();

        return array_map(function ($serv) {
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
                },  $serv->getModes()),
            ];
        }, $services);
    }

    public function getCurrentServices(int $uid): array
    {
        $user = $this->userRepo->find($uid);
        $services = $this->clientServService->getCurrentServicesWithModes($user);

        return array_map(function ($serv) {
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
                },  $serv->getModes()),
            ];
        }, $services);
    }

    /*
 * Активация услуги клиентом
 * */
    public function enableService(int $uid, int $modeId): bool
    {
        $user = $this->userRepo->find($uid);
        //$prodServMode = $this->

        // $this->userServModeService->enableService($prodServMode);

        return false;
    }

    /*
     * Отключение услуги клиентом
     * */
    public function disableService(int $uid, int $serviceId): bool
    {
        return false;
    }

    /*
     * Заморозка услуг клиентом
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