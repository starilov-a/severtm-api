<?php

namespace App\Modules\UserCabinet\Service;

use App\Modules\UserCabinet\Repository\TariffRepository;
use App\Modules\UserCabinet\Repository\UserRepository;
use App\Modules\UserCabinet\Service\Dto\Response\TariffDto;
use App\Modules\UserCabinet\Service\Exception\BusinessException;
use Symfony\Component\Config\Definition\BooleanNode;

class ClientTariffService
{
    protected $tariffRepo;
    protected $userRepo;
    protected $tariffService;
    public function __construct(
        TariffRepository $tariffRepository,
        UserRepository $userRepository,
        TariffService $tariffService
    )
    {
        $this->tariffRepo = $tariffRepository;
        $this->userRepo = $userRepository;
        $this->tariffService = $tariffService;
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

        // 2. Логика для клиента
        // 2.1. получаем новый тариф
        $newNextTariff = $this->tariffRepo->find($newTariffId);

        // 2.1.2 Является ли доступным тарифом на изменение самим клиентом
        if ($newNextTariff->canBeChangedByClient())
            throw new \Exception('Тариф не является доступным для смены клиентом');

        // 2.3 Новый тариф не является "Отключен от сети"
        if ($newNextTariff->isDisconnected())
            throw new \Exception('Невозможно выбрать тарифа "Отключён от сети"');

        // 2.2 Подвязка нового тарифа
        $this->tariffService->changeNextTariff($client, $newNextTariff);

        // 2.3 Запись истории
        $this->historyRepo->changeNextClintTariff(...);

        return true;
    }
}