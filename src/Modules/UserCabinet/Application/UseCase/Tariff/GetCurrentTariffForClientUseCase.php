<?php

namespace App\Modules\UserCabinet\Application\UseCase\Tariff;

use App\Modules\UserCabinet\Application\Dto\Response\TariffDto;
use App\Modules\UserCabinet\Domain\Repository\UserRepository;

class GetCurrentTariffForClientUseCase
{
    public function __construct(
        protected UserRepository $userRepo,
    ) {}

    public function handle(int $uid): TariffDto
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
}
