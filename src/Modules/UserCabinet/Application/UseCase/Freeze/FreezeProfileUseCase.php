<?php

namespace App\Modules\UserCabinet\Application\UseCase\Freeze;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\FreezeReason;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;
use App\Modules\UserCabinet\Domain\Dto\Request\CreateUserTaskDto;
use App\Modules\UserCabinet\Domain\RepositoryInterface\FreezeReasonRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class FreezeProfileUseCase
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected UserRepositoryInterface $userRepo,
        protected FreezeReasonRepositoryInterface $freezeReasonRepo,
        protected CreateTaskOnFreezeUseCase $createTaskOnFreezeUseCase,
    ) {}

    public function handle(int $uid, string $startDate, int $reasonId): bool
    {
        return $this->em->getConnection()->transactional(function () use (
            $uid,
            $startDate,
            $reasonId,
        ) {
            /* @var User $user*/
            $user = $this->userRepo->find($uid);

            /* @var FreezeReason $freezeReason */
            $freezeReason = $this->freezeReasonRepo->find($reasonId);

            $taskDto = new CreateUserTaskDto(
                $user,
                new \DateTimeImmutable($startDate),
                $freezeReason
            );

            $this->createTaskOnFreezeUseCase->handle($taskDto);

            return true;
        });
    }
}
