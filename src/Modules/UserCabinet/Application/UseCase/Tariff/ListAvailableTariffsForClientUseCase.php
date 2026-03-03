<?php

namespace App\Modules\UserCabinet\Application\UseCase\Tariff;

use App\Modules\UserCabinet\Domain\Repository\UserRepository;

class ListAvailableTariffsForClientUseCase
{
    public function __construct(
        protected UserRepository $userRepo,
        protected GetAvailableTariffsForClientUseCase $getAvailableTariffsForClientUseCase,
    ) {}

    public function handle(int $uid): array
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
