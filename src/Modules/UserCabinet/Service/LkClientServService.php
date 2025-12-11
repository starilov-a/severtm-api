<?php

namespace App\Modules\UserCabinet\Service;

use App\Modules\Common\Domain\Repository\ProdServModeRepository;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Service\Dto\Request\OptionsUserServModeDto;
use App\Modules\Common\Domain\Service\UserServModeService;
use App\Modules\Common\Domain\Service\UserServService;

class LkClientServService
{

    public function __construct(
        protected UserServService $clientServService,
        protected UserRepository  $userRepo,
        protected ProdServModeRepository  $prodServModeRepo,
        protected UserServModeService $userServModeService
    ){}

    public function listAvailableServices(): array
    {
//        $services = $this->clientServService->listAvailableServicesWithModes();
//
//        return array_map(function ($serv) {
//            return [
//                'servId' => $serv->getId(),
//                'name' => $serv->getName(),
//                'code' =>$serv->getStrCode(),
//                'modes' => array_map(function ($mode) {
//                    return [
//                        'modeId' => $mode->getId(),
//                        'name' => $mode->getName(),
//                        'code' =>$mode->getStrCode()
//                    ];
//                },  $serv->getModes()),
//            ];
//        }, $services);

        return [
            [
                'servId' =>4,
                'name' => 'Интернет',
                'code' => 'internet',
                'modes' => [
                    [
                        'modeId' => 1516,
                        'name' => 'Новгород: Unlim 6Мбит/сек (500.00 руб.)',
                        'code' => '8001',
                    ],
                    [
                        'modeId' => 1785,
                        'name' => 'Череповец: Стартовый (360.00 руб.)',
                        'code' => '12323',
                    ],
                    [
                        'modeId' => 1656,
                        'name' => 'ZNET Ярославль: Unlim 40-100 Мбит/с (650 руб)',
                        'code' => '23423',
                    ],
                    [
                        'modeId' => 1900,
                        'name' => 'ZNET Челябинск: Unlim 20-100 Мбит/с (650р)',
                        'code' => '2344323',
                    ],
                ],
            ],
            [
                'servId' => 9,
                'name' => 'Телефония',
                'code' => 'tv',
                'modes' => [
                    [
                        'modeId' => 4444,
                        'name' => '50р телефония',
                        'code' => 'standard',
                    ]
                ],
            ],
        ];
    }

    public function getCurrentServices(int $uid): array
    {
        $user = $this->userRepo->find($uid);
        $currentServs = $this->userServModeService->getCurrentServiceWithModes($user);

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
        }, $currentServs);
    }

    /*
    * Активация услуги клиентом
    * */
    public function enableService(int $uid, int $modeId): bool
    {
        $user = $this->userRepo->find($uid);
        $prodServMode = $this->prodServModeRepo->find($modeId);
        $options = new OptionsUserServModeDto();

        //Добавим комментарий, что пользователь сам активировал услугу
        $options->setComment('Активация услуги через личный кабинет');

        $this->userServModeService->addCurrentServiceMode($user, $prodServMode, $options);

        return true;
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
