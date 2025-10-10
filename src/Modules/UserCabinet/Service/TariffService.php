<?php

namespace App\Modules\UserCabinet\Service;

use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLog;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;
use App\Modules\UserCabinet\Entity\Tariff;
use App\Modules\UserCabinet\Entity\User;
use App\Modules\UserCabinet\Repository\AddressRepository;
use App\Modules\UserCabinet\Repository\FinPeriodRepository;
use App\Modules\UserCabinet\Repository\ServiceClientRepository;
use App\Modules\UserCabinet\Repository\TariffRepository;
use App\Modules\UserCabinet\Repository\UserRepository;
use App\Modules\UserCabinet\Repository\WebActionRepository;
use Doctrine\ORM\EntityManagerInterface;

class TariffService
{
    protected $webHistoryService;
    protected $tariffRepo;
    protected $userRepo;
    protected $addressRepo;
    protected $serviceClientRepo;
    protected $finPeriodRepo;
    protected $webActionRepo;
    private EntityManagerInterface $em;
    private LoggerService $loggerService;
    public function __construct(
        TariffRepository $tariffRepository,
        UserRepository $userRepository,
        AddressRepository $addressRepository,
        ServiceClientRepository $serviceClientRepository,
        FinPeriodRepository $finPeriodRepository,
        WebActionRepository $webActionRepository,
        WebHistoryService $webHistoryService,
        LoggerService $loggerService,
        EntityManagerInterface $em
    )
    {
        $this->tariffRepo = $tariffRepository;
        $this->userRepo = $userRepository;
        $this->addressRepo = $addressRepository;
        $this->serviceClientRepo = $serviceClientRepository;
        $this->finPeriodRepo = $finPeriodRepository;
        $this->webActionRepo = $webActionRepository;
        $this->em = $em;
        $this->webHistoryService = $webHistoryService;
        $this->loggerService = $loggerService;
    }

    public function changeNextTariff(User $user, Tariff $newNextTariff): bool
    {
        $currentNextTariff = $user->getNextTariff();
        $userRegion = $this->addressRepo->getRegionForAddressId($user->getAddress()->getId());
        $finPeriod = $this->finPeriodRepo->getNext();
        $webAction = $this->webActionRepo->findIdByCid('SET_NEXT_INET');

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
        return $this->em->getConnection()->transactional(function () use (
            $user,
            $newNextTariff,
            $finPeriod,
            $webAction
        ) {
            $this->finPeriodRepo->clearForFinPeriod($finPeriod->getId(), $user->getId());
            // 7. чистка любых user_serv_modes для тарифов за будущие периоды
            $this->tariffRepo->clearAssignedTariffs($finPeriod->getId(), $user->getId());
            // 8. Добавление нового user_serv_mode (user_serv_mode + users)
            $this->tariffRepo->setNextTariffForClient($finPeriod->getId(), $user->getId(), $newNextTariff->getId());

            $this->userRepo->changeNextTariff($user->getId(), $newNextTariff->getId());

            // 9. запись в историю об успехе
            $this->loggerService->log(new BusinessLog($user->getId(), $webAction->getId(), 'Пользователь ' . $user->getId() . ' успешно сменил тариф('. $newNextTariff->getId() .')' , true));

            return true;
        });
    }
}