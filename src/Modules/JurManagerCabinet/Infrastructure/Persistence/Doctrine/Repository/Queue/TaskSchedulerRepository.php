<?php

namespace App\Modules\JurManagerCabinet\Infrastructure\Persistence\Doctrine\Repository\Queue;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserTask;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserTaskParameter;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\EnumParameterRepository;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\UserRepository;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\UserTaskParameterRepository;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\UserTaskRepository;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\UserTaskStateRepository;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\UserTaskTypeRepository;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\WebUserRepository;
use App\Modules\JurManagerCabinet\Application\Dto\Request\Queue\ScheduleReissueTaskDto;
use App\Modules\JurManagerCabinet\Domain\Entity\Task\ScheduledTask;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\TaskSchedulerInterface;

class TaskSchedulerRepository implements TaskSchedulerInterface
{
    public function __construct(
        private UserTaskRepository $userTaskRepo,
        private UserTaskStateRepository $userTaskStateRepo,
        private UserTaskTypeRepository $userTaskTypeRepo,
        private UserRepository $userRepo,
        private WebUserRepository $webUserRepo,
        private EnumParameterRepository $enumParamRepo,
        private UserTaskParameterRepository $userTaskParameterRepo,
    ) {}

    public function scheduleForReissue(ScheduleReissueTaskDto $dto): ScheduledTask
    {
        $tableTask = new UserTask();
        $tableTask->setType($this->userTaskTypeRepo->findOneBy(['code' => $dto->getTaskType()]));
        $tableTask->setState($this->userTaskStateRepo->findOneBy(['code' => $dto->getTaskState()]));
        $tableTask->setUser($this->userRepo->find($dto->getContractId()));
        $tableTask->setAuthor($this->webUserRepo->find($dto->getManagerId()));
        $tableTask->setCreatedAt($dto->getCreateAt());
        $tableTask->setStartTime($dto->getStartAt());
        $tableTask->setComment($dto->getComment());
        $tableTask = $this->userTaskRepo->save($tableTask);

        $reissueDto = $dto->getContractReissueProcess();
        $this->saveParameter($tableTask, 'reissue_inn', (string) $reissueDto->getNewInn());
        $this->saveParameter($tableTask, 'reissue_fio', $reissueDto->getFio());
        $this->saveParameter($tableTask, 'reissue_login', $reissueDto->getLogin());
        $this->saveParameter($tableTask, 'reissue_phone', $reissueDto->getPhone());
        $this->saveParameter($tableTask, 'reissue_old_contract', $dto->getContractId());
        $this->saveParameter($tableTask, 'reissue_new_contract', $dto->getNewContractId());
        $this->saveParameter($tableTask, 'reissue_comment', $reissueDto->getComment());

        return new ScheduledTask(
            $tableTask->getId(),
            $tableTask->getType()->getCode(),
            $tableTask->getState()->getCode(),
            $tableTask->getUser()->getId(),
            $tableTask->getAuthor()->getUid(),
            new \DateTimeImmutable($tableTask->getStartTime()->format('Y-m-d H:i:s')),
            [],
            0
        );
    }

    private function saveParameter(UserTask $task, string $code, ?string $value): void
    {
        $type = $this->enumParamRepo->find($code);
        if ($type === null) {
            return;
        }

        $parameter = new UserTaskParameter();
        $parameter->setTask($task);
        $parameter->setType($type);
        $parameter->setValue($value);

        $this->userTaskParameterRepo->save($parameter);
    }
}
