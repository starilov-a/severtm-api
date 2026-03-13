<?php

namespace App\Modules\UserCabinet\Application\UseCase\Service;

use App\Modules\UserCabinet\Domain\Service\UserServService;

class ListAvailableServicesUseCase
{
    public function __construct(
        protected UserServService $clientServService,
    ) {}

    public function handle(): array
    {
        $services = $this->clientServService->listAvailableServicesWithModes();

        return array_map(function ($serv) {
            return [
                'id' => $serv->getId(),
                'name' => $serv->getName(),
                'code' => $serv->getStrCode(),
                'modes' => array_map(function ($mode) {
                    return [
                        'id' => $mode->getId(),
                        'price'=> $mode->getProdServModeCost()->getCost(),
                        'name' => $mode->getName(),
                        'code' => $mode->getStrCode()
                    ];
                }, $serv->getModes()),
            ];
        }, $services);
    }
}
