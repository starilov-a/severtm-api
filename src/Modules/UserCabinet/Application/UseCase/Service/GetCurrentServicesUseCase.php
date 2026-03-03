<?php

namespace App\Modules\UserCabinet\Application\UseCase\Service;

use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;
use App\Modules\UserCabinet\Domain\Service\UserServModeService;

class GetCurrentServicesUseCase
{
    public function __construct(
        protected UserRepositoryInterface $userRepo,
        protected UserServModeService $userServModeService,
    ) {}

    public function handle(int $uid): array
    {
        $user = $this->userRepo->find($uid);
        $currentServs = $this->userServModeService->getCurrentServiceWithModes($user);

        return array_map(function ($serv) {
            return [
                'id' => $serv->getId(),
                'name' => $serv->getName(),
                'code' => $serv->getStrCode(),
                'type' => 'ProductService',
                'modes' => array_map(function ($userMode) {
                    return [
                        'usmid' => $userMode->getId(),
                        'name' => $userMode->getMode()->getName(),
                        'code' => $userMode->getMode()->getStrCode(),
                        'type' => 'UserServMode',
                    ];
                }, $serv->getUserModes()),
            ];
        }, $currentServs);
    }
}
