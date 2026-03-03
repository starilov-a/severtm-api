<?php

namespace App\Modules\UserCabinet\Application\UseCase\Break;

use App\Modules\UserCabinet\Domain\Entity\User;
use App\Modules\UserCabinet\Domain\Repository\UserRepository;
use App\Modules\UserCabinet\Domain\Repository\WebActionRepository;
use App\Modules\UserCabinet\Domain\Service\BreakService;
use App\Modules\UserCabinet\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\UserCabinet\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\UserCabinet\Infrastructure\Service\Logger\LoggerService;
use Doctrine\ORM\EntityManagerInterface;

class TakeBreakForOneDayUseCase
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected LoggerService         $loggerService,
        protected BreakService          $breakService,

        protected UserRepository        $userRepo,
        protected WebActionRepository   $webActionRepo,
    ) {}
    public function handle(int $uid): bool
    {
        return $this->em->getConnection()->transactional(function () use (
            $uid,
        ) {
            $user = $this->userRepo->find($uid);

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
        });


        return true;
    }
}