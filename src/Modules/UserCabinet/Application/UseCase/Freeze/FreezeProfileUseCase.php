<?php

namespace App\Modules\UserCabinet\Application\UseCase\Freeze;

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
            $taskDto = new CreateUserTaskDto(
                $this->userRepo->find($uid),
                new \DateTimeImmutable($startDate),
                $this->freezeReasonRepo->find($reasonId)
            );

            $this->createTaskOnFreezeUseCase->handle($taskDto);

            return true;
        });
    }
}
