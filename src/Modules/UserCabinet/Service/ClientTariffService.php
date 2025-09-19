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
        //1. Получаем пользака
        $user = $this->userRepo->find($uid);

        //2.Логика
        // 2.1. получаем новый тариф
        $newNextTariff = $this->tariffRepo->find($newTariffId);
        $oldNextTariff = $user->getCurrentNextTariff();

        // 2.1.2 Является ли доступным тарифом на измеение
        if ($this->tariffRepo->belongsToGroup($newNextTariff->getId(), 'changeByClient'))
            throw new \Exception('Тариф не явялется доступным для смены клиентом');

        // 2.3 новый тариф не является "Отключен от сети"
        if ($this->tariffRepo->tariffIsDisconnected())
            throw new \Exception('Невозможно ввыбрать тарифа "Отключён от сети"');

        // 2.2 Подвязка нового тарифа
        $this->tariffService->changeNextTariff($user, $newNextTariff);

        // 2.3 Запись истории
        $this->historyRepo->changeNextClintTariff(...);

        return true;
    }
}