<?php

namespace App\Modules\UserCabinet\Service;

use App\Modules\UserCabinet\Repository\TariffRepository;
use App\Modules\UserCabinet\Repository\UserRepository;
use App\Modules\UserCabinet\Repository\WebActionRepository;
use App\Modules\UserCabinet\Service\Dto\Response\TariffDto;

class ClientTariffService
{
    protected $tariffRepo;
    protected $userRepo;
    protected $tariffService;
    protected $webHistoryService;
    protected $webActionRepo;
    public function __construct(
        TariffRepository $tariffRepository,
        UserRepository $userRepository,
        WebActionRepository $webActionRepository,
        TariffService $tariffService,
        WebHistoryService $webHistoryService
    )
    {
        $this->tariffRepo = $tariffRepository;
        $this->userRepo = $userRepository;
        $this->webActionRepo = $webActionRepository;
        $this->tariffService = $tariffService;
        $this->webHistoryService = $webHistoryService;
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
    public function changeNextTariff(int $uid, int $newTariffId): Bool
    {
        // 1. Получаем клиента
        $client = $this->userRepo->find($uid);
        $webAction = $this->webActionRepo->findIdByCid('WA_USERS_CHANGE_TARIFFS');
        // 2. Логика для клиента
        // 2.1. получаем новый тариф
        $newNextTariff = $this->tariffRepo->find($newTariffId);

        // 2.1.2 Является ли доступным тарифом на изменение самим клиентом
        if ($newNextTariff->canBeChangedByClient())
            throw new \DomainException('Тариф не является доступным для смены клиентом');

        // 2.3 Новый тариф не является "Отключен от сети"
        if ($newNextTariff->isDisconnected())
            throw new \Exception('Невозможно выбрать тарифа "Отключён от сети"');

        // 2.2 Подвязка нового тарифа
        $this->tariffService->changeNextTariff($client, $newNextTariff);

        // 2.3 Запись истории
        $this->webHistoryService->writeWebLog($uid, $webAction, 'Пользователь ' . $uid . ' успешно сменил тариф('. $newTariffId .')' , true);

        return true;
    }
}