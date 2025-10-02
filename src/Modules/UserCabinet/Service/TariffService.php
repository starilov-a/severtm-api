<?php

namespace App\Modules\UserCabinet\Service;

use App\Modules\UserCabinet\Entity\Tariff;
use App\Modules\UserCabinet\Entity\User;
use App\Modules\UserCabinet\Repository\AddressRepository;
use App\Modules\UserCabinet\Repository\FinPeriodRepository;
use App\Modules\UserCabinet\Repository\ServiceClientRepository;
use App\Modules\UserCabinet\Repository\TariffRepository;
use App\Modules\UserCabinet\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class TariffService
{
    protected $tariffRepo;
    protected $userRepo;
    protected $addressRepo;
    protected $serviceClientRepo;
    protected $finPeriodRepo;
    private EntityManagerInterface $em;
    public function __construct(
        TariffRepository $tariffRepository,
        UserRepository $userRepository,
        AddressRepository $addressRepository,
        ServiceClientRepository $serviceClientRepository,
        FinPeriodRepository $finPeriodRepository,
        EntityManagerInterface $em
    )
    {
        $this->tariffRepo = $tariffRepository;
        $this->userRepo = $userRepository;
        $this->addressRepo = $addressRepository;
        $this->serviceClientRepo = $serviceClientRepository;
        $this->finPeriodRepo = $finPeriodRepository;
        $this->em = $em;
    }

    public function changeNextTariff(User $user, Tariff $newNextTariff): bool
    {
        $currentNextTariff = $user->getNextTariff();
        $userRegion = $this->addressRepo->getRegionForAddressId($user->getAddress()->getId());
        $finPeriod = $this->finPeriodRepo->getNext();

        // ЛОГИКА
        if (!$finPeriod)
            throw new \Exception('Не найден следующий финансовый период');
        if (!$this->tariffRepo->isAvailableForRegion($newNextTariff->getId(), $userRegion->getId()))
            throw new \Exception('Тариф не соответствует адресу');
        // 4. если имеется аренда - нельзя disconnected
        if ($this->serviceClientRepo->hasRentNow($user->getId()))
            throw new \Exception('Присутствует услуга аренды');
        // 5 Тарифы одинаковые
        if ($newNextTariff->getId() == $currentNextTariff->getId())
            throw new \Exception('Старый и новый тариф совпадают');

        // ДЕЙСТВИЯ
        $connection = $this->em->getConnection();
        return $connection->transactional(function () use (
            $user,
            $newNextTariff,
            $finPeriod
        ) {
            $this->finPeriodRepo->clearForFinPeriod($finPeriod->getId(), $user->getId());
            // 7. чистка любых user_serv_modes для тарифов за будущие периоды
            $this->tariffRepo->clearAssignedTariffs($finPeriod->getId(), $user->getId());
            // 8. Добавление нового user_serv_mode (user_serv_mode + users)
            $this->tariffRepo->setNextTariffForClient($finPeriod->getId(), $user->getId(), $newNextTariff->getId());
            $this->userRepo->changeNextTariff($user->getId(), $newNextTariff->getId());

            // 9. запись в историю об успехе
            $this->historyRepo->changeNextTariff(...);
            return true;
        });
    }
}