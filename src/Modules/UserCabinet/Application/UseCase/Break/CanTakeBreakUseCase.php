<?php

namespace App\Modules\UserCabinet\Application\UseCase\Break;

use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;
use App\Modules\UserCabinet\Domain\Service\BreakService;

class CanTakeBreakUseCase
{
    public function __construct(
        protected UserRepositoryInterface $userRepo,
        protected BreakService $breakService,
    ) {}
    public function handle(int $uid)
    {
        $user = $this->userRepo->find($uid);
        // проверка для клиента
        $data = $this->breakService->getBreakStatusForUser($user);

        $breakStatus = [
            'isAvailable' => $data['isAvailable'],
            'isActive' => $data['isActive'],
            'count' => $data['countAvailableBreaks'],
        ];

        if ($data['isActive'])
            $breakStatus['endDate'] = $data['deadlineDate']->format('Y-m-d');

        return $breakStatus;
    }
}