<?php

namespace App\Modules\UserCabinet\Service;

use App\Modules\UserCabinet\Entity\Tariff;
use App\Modules\UserCabinet\Entity\User;
use App\Modules\UserCabinet\Repository\TariffRepository;
use App\Modules\UserCabinet\Repository\UserRepository;

class TariffService
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

    public function changeNextTariff(User $user, Tariff $newNextTariff)
    {
        $oldNextTariff = $user->getCurrentNextTariff();
        // ВАЛИДАЦИЯ
        //
        // ЛОГИКА
        // 1. не пакетный режим (пока оставим)
        // 2. проверка тарифа по адресу
        if (!$this->tariffRepo->issetForAddress($newNextTariff->getId(), $user->getAddressId()))
            throw new \Exception('Тариф не соответстует адресу');
        // 4. если имеется аренда - нельзя disconnected
        if ($this->serviceClinetRepo->issetRentService($user->getId()))
            throw new \Exception('Присутствует услуга аренды');
        // 5 Тарифы одинаковые
        if ($newNextTariff->getId() == $oldNextTariff->getId())
            throw new \Exception('Старый и новый тариф совпадают');

        // ДЕЙСТВИЯ
        // 5. корретный finid - получим тут
        $finPeriod = $this->finPeriodRepo->getNext();
        // 6. чистка finid
        $this->finPeriodRepo->clearForNextFinPeriods($finPeriod->getId());
        // 7. чистка servpack - не используется / чистка любых user_serv_modes за будущие периоды
        $this->userServModeRepo->clearForNextTariff($finPeriod->getId(), $user->getId());

        // 8. Добавление нового user_serv_mode (user_serv_mode + users)
        $this->tariffRepo->setNextTariffForClient($finPeriod, $user->getId(), $newNextTariff->getId());
        $this->userRepo->changeNextTariff($user->getId(), $newNextTariff->getId());

        // 9. запись в историю об успехе
        $this->historyRepo->changeNextTariff(...);
    }
}