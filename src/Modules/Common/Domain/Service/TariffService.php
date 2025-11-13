<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\Tariff;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Repository\AddressRepository;
use App\Modules\Common\Domain\Repository\FinPeriodRepository;
use App\Modules\Common\Domain\Repository\ServiceClientRepository;
use App\Modules\Common\Domain\Repository\TariffRepository;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Repository\WebActionRepository;
use App\Modules\Common\Domain\Service\Dto\Request\TariffFilterDto;
use App\Modules\Common\Infrastructure\Exception\ImportantBusinessException;
use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;
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
        $this->loggerService = $loggerService;
    }

    public function changeNextTariff(User $user, Tariff $newNextTariff): bool
    {
        $currentNextTariff = $user->getNextTariff();
        $userRegion = $user->getRegion();
        $finPeriod = $this->finPeriodRepo->getNext();
        $webAction = $this->webActionRepo->findIdByCid('SET_NEXT_INET');

        // ЛОГИКА
        if (!$finPeriod)
            throw new ImportantBusinessException($user->getId(), $webAction->getId(),'Не найден следующий финансовый период');

        if (!$this->tariffRepo->isAvailableForRegion($newNextTariff->getId(), $userRegion->getId()))
            throw new ImportantBusinessException($user->getId(), $webAction->getId(), 'Тариф не соответствует адресу');

        // 4. если имеется аренда - нельзя disconnected
        if ($this->serviceClientRepo->hasRentNow($user->getId()))
            throw new ImportantBusinessException($user->getId(), $webAction->getId(),'Присутствует услуга аренды');

        // 5 Тарифы одинаковые
        if ($newNextTariff->getId() == $currentNextTariff->getId())
            throw new ImportantBusinessException($user->getId(), $webAction->getId(),'Старый и новый тариф совпадают');

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
            $this->loggerService->businessLog(new BusinessLogDto(
                $user->getId(),
                $webAction->getId(),
                'Тариф на следующий месяц для пользователя ' . $user->getId() . ' успешно изменен тариф - ' . $newNextTariff->getName(). '(' . $newNextTariff->getId() .')' ,
                true
            ));

            return true;
        });
    }

    public function getTariffsForClient(User $user, TariffFilterDto $dto = new TariffFilterDto()): array
    {
        //1. Тарифы активные
        $dto->setActiveStatus(true);

        //2. Тариф имеет группу, обозначающую необходимый регион
        $userRegion = $user->getRegion();
        array_map(function ($region) use ($dto) {
            $dto->addRegionGroupCodes($region);
        }, [
            1 => 'velikij_novgorod_tariffs',
            2 => 'cherepevets_tariffs',
            3 => 'chelyzbinsk_tariffs',
            4 => 'yaroslavl_tariffs'
        ]);

        return $this->tariffRepo->getTariffs($dto);
    }
}