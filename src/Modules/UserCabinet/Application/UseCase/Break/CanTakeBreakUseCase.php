<?php

namespace App\Modules\UserCabinet\Application\UseCase\Break;

use App\Modules\UserCabinet\Domain\Repository\UserRepository;
use App\Modules\UserCabinet\Domain\Service\BreakService;
use Doctrine\ORM\EntityManagerInterface;

class TakeBreakForOneDayUseCase
{
    public function __construct(
        protected UserRepository $userRepo,
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