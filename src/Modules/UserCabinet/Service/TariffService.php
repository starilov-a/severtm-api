<?php

namespace App\Modules\UserCabinet\Service;

use App\Modules\UserCabinet\Repository\TariffRepository;
use App\Modules\UserCabinet\Repository\UserRepository;
use App\Modules\UserCabinet\Service\Dto\Response\TariffDto;
use Symfony\Component\Config\Definition\BooleanNode;

class TariffService
{
    protected $tariffRepo;
    protected $userRepo;
    public function __construct(
        TariffRepository $tariffRepository,
        UserRepository $userRepository
    )
    {
        $this->tariffRepo = $tariffRepository;
        $this->userRepo = $userRepository;
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

    public function changeCurrentTariff(int $uid, int $newTariffId): Bool
    {
        //1. Получаем пользака
        $user = $this->userRepo->find($uid);

        // 2. Логика
        // 2.1. получаем новый тариф
        $newTariff = $this->tariffRepo->find($newTariffId);
        $currentTariff = $user->getCurrentTariff();

        // 2.2 Тарифы одинаковые
        if ($newTariff->getId() == $currentTariff->getId())
            throw new \Exception('Старый и новый тариф совпадают');

        // 2.3 Вычисление коэффициента для перерасчета

        // 2.2 Подвязка нового тарифа
        $this->tariffRepo->changeCurrentTariff(...);

        // 2.3 Перерасчет
        $this->

        // 2.4 Саписание за новый


        // 2.3 Запись истории
        $this->historyRepo->changeCurrentTariff(...);




        //3. Меняем тариф

        //4. возвращаем статус
        return true;




    //    1. можно ли менять тариф в течении какого либо времени
    // 2.
        // 2. разнцаи месцево когда устанавливали и меняют
        // 3. меняет ли в принципе
        // Либо юрик лиюо физ на юрике
        //






    }
}