<?php

namespace App\Modules\UserCabinet\Service;

use App\Modules\Common\Infrastructure\Exception\BusinessException;
use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;
use App\Modules\UserCabinet\Repository\TariffRepository;
use App\Modules\UserCabinet\Repository\UserRepository;
use App\Modules\UserCabinet\Repository\WebActionRepository;
use App\Modules\UserCabinet\Service\Dto\Request\TariffFilterDto;
use App\Modules\UserCabinet\Service\Dto\Response\TariffDto;

class ClientTariffService
{
    protected TariffRepository $tariffRepo;
    protected UserRepository $userRepo;
    protected TariffService $tariffService;
    protected WebActionRepository $webActionRepo;
    protected LoggerService $loggerService;
    public function __construct(
        TariffRepository $tariffRepository,
        UserRepository $userRepository,
        WebActionRepository $webActionRepository,
        TariffService $tariffService,
        LoggerService $loggerService
    )
    {
        $this->tariffRepo = $tariffRepository;
        $this->userRepo = $userRepository;
        $this->webActionRepo = $webActionRepository;
        $this->tariffService = $tariffService;
        $this->loggerService = $loggerService;
    }
    public function getCurrentTariff(int $uid): TariffDto
    {
        $user = $this->userRepo->find($uid);
        $currentTariff = $user->getCurrentTariff();

        return new TariffDto(
            $currentTariff->getName(),
            $currentTariff->getPrice()
        );
    }

    // Изменение тарифа на след месяц
    public function changeNextTariff(int $uid, int $newTariffId): bool
    {
        // 1. Получаем клиента
        $client = $this->userRepo->find($uid);

        //TODO: сделать собственный экшен "Изменение тарифа самим клиентом"
        $webAction = $this->webActionRepo->findIdByCid('WA_USERS_CHANGE_TARIFFS');
        // 2. Логика для клиента
        // 2.1. получаем новый тариф
        $newNextTariff = $this->tariffRepo->find($newTariffId);

        // 2.1.2 Является ли доступным тарифом на изменение самим клиентом
        if (!$newNextTariff->canBeChangedByClient())
            throw new BusinessException('Тариф не является доступным для смены клиентом');

        // 2.3 Новый тариф не является "Отключен от сети"
        if ($newNextTariff->isDisconnected())
            throw new BusinessException('Невозможно выбрать тарифа "Отключён от сети"');

        // 2.2 Подвязка нового тарифа
        $this->tariffService->changeNextTariff($client, $newNextTariff);

        // 2.3 Запись истории
        $this->loggerService->businessLog(new BusinessLogDto(
            $uid,
            $webAction->getId(),
            'Пользователь ' . $uid . ' успешно сменил тариф - ' . $newNextTariff->getName() . '('. $newTariffId .')' ,
            true)
        );

        return true;
    }


    public function getAvailableTariffs(int $uid): array
    {
        $user = $this->userRepo->find($uid);
        $userRegion = $user->getAddress()->getRegion();
        $dto = new TariffFilterDto();

        //1. Тарифы активные
        $dto->setActiveStatus(true);

        //2. Тарифы стоят больше чем текущий
        $currentTariff = $user->getCurrentTariff();
        $dto->setMinPrice($currentTariff->getPrice());

        //3. Тариф имеет группу, обозначающая необходимый регион
        array_map(function ($region) use ($dto) {
            $dto->addRegionGroupCodes($region);
        }, [
            1 => 'velikij_novgorod_tariffs',
            2 => 'cherepevets_tariffs',
            3 => 'chelyzbinsk_tariffs',
            4 => 'yaroslavl_tariffs'
        ]);

        //4. Тариф доступен для изменения:
        $dto->addGroupCodes('canBeChangeByClient');

        $tariffs = $this->tariffRepo->getTariffs($dto);

        return array_map(function ($tariff) {
            return [
                'id' => $tariff->getId(),
                'name' => $tariff->getName(),
                'price' => $tariff->getPrice()
            ];
        }, $tariffs);
    }
}
