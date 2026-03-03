<?php

namespace App\Modules\UserCabinet\Application;

use App\Modules\Common\Application\UseCase\Tariff\ChangeNextTariffUseCase;
use App\Modules\Common\Domain\Repository\TariffRepository;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Repository\WebActionRepository;
use App\Modules\Common\Domain\Service\TariffService;
use App\Modules\UserCabinet\Application\Dto\Response\TariffDto;
use App\Modules\UserCabinet\Application\UseCase\Tariff\ClientChangeNextTariffUseCase;
use App\Modules\UserCabinet\Application\UseCase\Tariff\GetAvailableTariffsForClientUseCase;
use App\Modules\UserCabinet\Infrastructure\Service\Logger\LoggerService;
use Doctrine\ORM\EntityManagerInterface;

class LkClientTariffService
{
    public function __construct(
        protected EntityManagerInterface        $em,

        protected TariffRepository              $tariffRepo,
        protected UserRepository                $userRepo,
        protected WebActionRepository           $webActionRepo,

        protected LoggerService                 $loggerService,
        protected TariffService                 $tariffService,

        protected GetAvailableTariffsForClientUseCase $getAvailableTariffsForClientUseCase,
        protected ClientChangeNextTariffUseCase  $clientChangeNextTariffUseCase,
    ) {}
    public function getCurrentTariff(int $uid): TariffDto
    {
        $user = $this->userRepo->find($uid);
        $currentTariff = $user->getCurrentTariff();

        return new TariffDto(
            $currentTariff->getId(),
            $currentTariff->getName(),
            $currentTariff->getPrice(),
            !($currentTariff->getId() === 1)
        );
    }

    // Изменение тарифа на след месяц
    public function changeNextTariff(int $uid, int $newTariffId): bool
    {
        return $this->em->getConnection()->transactional(function () use (
            $uid,
            $newTariffId,
        ) {
            $client = $this->userRepo->find($uid);
            $newNextTariff = $this->tariffRepo->find($newTariffId);

            $this->clientChangeNextTariffUseCase->handle($client, $newNextTariff);

            return true;
        });
    }


    public function getAvailableTariffs(int $uid): array
    {
        $client = $this->userRepo->find($uid);

        $tariffs = $this->getAvailableTariffsForClientUseCase->handle($client);

        return array_map(function ($tariff) {
            return [
                'id' => $tariff->getId(),
                'name' => $tariff->getName(),
                'price' => $tariff->getPrice()
            ];
        }, $tariffs);
    }
}
