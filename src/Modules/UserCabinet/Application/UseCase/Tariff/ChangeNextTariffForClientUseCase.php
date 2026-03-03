<?php

namespace App\Modules\UserCabinet\Application\UseCase\Tariff;

use App\Modules\UserCabinet\Domain\Repository\TariffRepository;
use App\Modules\UserCabinet\Domain\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class ChangeNextTariffForClientUseCase
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected TariffRepository $tariffRepo,
        protected UserRepository $userRepo,
        protected ClientChangeNextTariffUseCase $clientChangeNextTariffUseCase,
    ) {}

    public function handle(int $uid, int $newTariffId): bool
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
}
