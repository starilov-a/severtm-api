<?php

namespace App\Modules\UserCabinet\Service;

use App\Modules\Common\Application\UseCase\Freeze\CreateTaskOnFreezeUseCase;
use App\Modules\Common\Application\UseCase\Freeze\UnfreezeInternetNoJuridicalUserUseCase;
use App\Modules\Common\Domain\Entity\FreezeReason;
use App\Modules\Common\Domain\Repository\FreezeReasonRepository;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Repository\UserTaskRepository;
use App\Modules\Common\Domain\Repository\UserTaskStateRepository;
use App\Modules\Common\Domain\Service\Dto\Request\CreateUserTaskDto;
use App\Modules\Common\Domain\Service\FreezeService;
use Doctrine\ORM\EntityManagerInterface;

class LkFreezeService
{
    public function __construct(
        protected EntityManagerInterface                    $em,

        protected FreezeService                             $freezeService,
        protected UserRepository                            $userRepo,
        protected FreezeReasonRepository                    $freezeReasonRepo,
        protected UserTaskRepository                        $userTaskRepo,
        protected UserTaskStateRepository                   $userTaskStateRepo,

        protected CreateTaskOnFreezeUseCase                 $createTaskOnFreezeUseCase,
        protected UnfreezeInternetNoJuridicalUserUseCase    $unfreezeInternetNoJuridicalUserUseCase,

    ) {}

    public function getReasonForFreeze(): array
    {
        $reasons = $this->freezeService->getClientReasonForFreeze();

        return array_map(static function (FreezeReason $reason): array {
            return [
                'id' => $reason->getId(),
                'name' => $reason->getName(),
            ];
        }, $reasons);
    }

    /*
     * Заморозка клиента
     * */
    public function freezeProfile(int $uid, string $startDate, int $reasonId): bool
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

    /*
     * Разморозка клиента
     * */
    public function unfreezeProfile(int $uid): bool
    {
        return $this->em->getConnection()->transactional(function () use (
            $uid,
        ) {
            $user = $this->userRepo->find($uid);

            $this->unfreezeInternetNoJuridicalUserUseCase->handle($user);

            return true;
        });
    }

    public function getFreezeStatus($uid): array
    {
        $user = $this->userRepo->find($uid);

        $freezeStatus = $this->freezeService->getUserFreezeStatus($user);
        return $freezeStatus->toArray();
    }
}