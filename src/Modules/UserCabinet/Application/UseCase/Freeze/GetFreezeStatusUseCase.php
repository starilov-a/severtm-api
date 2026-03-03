<?php

namespace App\Modules\UserCabinet\Application\UseCase\Freeze;

use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;
use App\Modules\UserCabinet\Domain\Service\FreezeService;

class GetFreezeStatusUseCase
{
    public function __construct(
        protected UserRepositoryInterface $userRepo,
        protected FreezeService $freezeService,
    ) {}

    public function handle(int $uid): array
    {
        $user = $this->userRepo->find($uid);

        $freezeStatus = $this->freezeService->getUserFreezeStatus($user);
        return $freezeStatus->toArray();
    }
}
