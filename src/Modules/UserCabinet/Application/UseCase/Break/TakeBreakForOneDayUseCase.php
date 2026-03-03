<?php

namespace App\Modules\UserCabinet\UseCase\Break;

use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Repository\WebActionRepository;
use App\Modules\Common\Domain\Service\BreakService;
use App\Modules\UserCabinet\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\UserCabinet\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\UserCabinet\Infrastructure\Service\Logger\LoggerService;

class TakeBreakForOneDayUseCase
{
    public function __construct(
        protected LoggerService         $loggerService,
        protected BreakService          $breakService,

        protected UserRepository        $userRepo,
        protected WebActionRepository   $webActionRepo,
    ) {}
    public function handle(User $user): bool
    {
        $master = $this->userRepo->find(UserSessionService::getUserId());
        $webAction = $this->webActionRepo->findIdByCid('WA_USERS_GIVECREDIT');

        $this->breakService->takeBreakForUser($user, 1);

        $this->loggerService->businessLog(new BusinessLogDto(
            $master->getId(),
            $webAction->getId(),
            "Для пользователя {$user->getId()} успешно применена отсрочка на 1 день).",
            true
        ));

        return true;
    }
}