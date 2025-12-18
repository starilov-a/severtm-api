<?php

namespace App\Modules\UserCabinet\Service;

use App\Modules\Common\Domain\Entity\FreezeReason;
use App\Modules\Common\Domain\Repository\FreezeReasonRepository;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Repository\UserTaskRepository;
use App\Modules\Common\Domain\Repository\WebUserRepository;
use App\Modules\Common\Domain\Service\Dto\Request\CreateUserTaskDto;
use App\Modules\Common\Domain\Service\FreezeService;
use App\Modules\Common\Infrastructure\Exception\BusinessException;
use Doctrine\ORM\EntityManagerInterface;

class LkFreezeService
{
    public function __construct(
        protected FreezeService             $freezeService,
        protected UserRepository            $userRepo,
        protected FreezeReasonRepository    $freezeReasonRepo,
        protected UserTaskRepository        $userTaskRepo,
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
        $taskDto = new CreateUserTaskDto(
            $this->userRepo->find($uid),
            new \DateTimeImmutable($startDate),
            $this->freezeReasonRepo->find($reasonId)
        );

        $this->freezeService->createFreezeUserTask($taskDto);

        return true;
    }

    /*
     * Разморозка клиента
     * */
    public function unfreezeProfile(int $uid): bool
    {
        throw new BusinessException('Обратитесь к менеджеру для разморозки');

        $taskDto = $this->userTaskRepo->findOneBy(['user' => $this->userRepo->find($uid)]);

        $this->freezeService->createUnfreezeUserTask($taskDto);

        return true;
    }

    public function getFreezeStatus($uid): array
    {
        $user = $this->userRepo->find($uid);

        return $this->freezeService->getUserFreezeStatus($user);
    }
}