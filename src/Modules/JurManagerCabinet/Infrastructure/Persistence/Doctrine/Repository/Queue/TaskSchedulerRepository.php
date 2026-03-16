<?php

namespace App\Modules\JurManagerCabinet\Infrastructure\Persistence\Doctrine\Repository\Queue;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserTask;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\UserRepository;
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
        private UserTaskRepository          $userTaskRepo,
        private UserTaskStateRepository     $userTaskStateRepo,
        private UserTaskTypeRepository      $userTaskTypeRepo,
        private UserRepository              $userRepo,
        private WebUserRepository           $webUserRepo,
    ) {}
    public function scheduleForReissue(ScheduleReissueTaskDto $dto): ScheduledTask
    {
        // Добавление значений по задаче
        $tableTask = new UserTask();
        $tableTask->setType($this->userTaskTypeRepo->findOneBy(['code' => $dto->getTaskType()]));
        $tableTask->setState($this->userTaskStateRepo->findOneBy(['code' => $dto->getTaskState()]));
        $tableTask->setUser($this->userRepo->find($dto->getContractId()));
        $tableTask->setAuthor($this->webUserRepo->find($dto->getManagerId()));
        $tableTask->setCreatedAt($dto->getCreateAt());
        $tableTask->setStartTime($dto->getStartAt());
        $tableTask->setComment($dto->getComment());
        $tableTask = $this->userTaskRepo->save($tableTask);

        // Добавление параметров задачи
        // TODO: Создать задачу


        return new ScheduledTask(
            $tableTask->getId(),
            $tableTask->getType()->getCode(),
            $tableTask->getState()->getCode(),
            $tableTask->getUser()->getId(),
            $tableTask->getAuthor()->getUid(),
            new \DateTimeImmutable($tableTask->getStartTime()),
            [],
            0
        );
    }
}