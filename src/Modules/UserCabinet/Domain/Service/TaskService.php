<?php

namespace App\Modules\UserCabinet\Domain\Service;

use App\Modules\UserCabinet\Domain\Entity\UserTask;
use App\Modules\UserCabinet\Domain\Persistence\UnitOfWorkInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserTaskRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserTaskStateRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\WebActionRepositoryInterface;
use App\Modules\UserCabinet\Domain\Service\Dto\Request\CreateUserTaskDto;
use App\Modules\UserCabinet\Infrastructure\Exception\ImportantBusinessException;
use App\Modules\UserCabinet\Infrastructure\Service\Auth\Service\UserSessionService;

class TaskService
{
    public function __construct(
        protected UserRepositoryInterface $userRepo,
        protected WebActionRepositoryInterface $webActionRepo,
        protected UserTaskStateRepositoryInterface $taskStateRepo,
        protected UserTaskRepositoryInterface $userTaskRepo,
    ){}
    public function createUserTask(CreateUserTaskDto $createUserTaskDto): UserTask
    {
        $master = $this->userRepo->find(UserSessionService::getUserId());
        $webAction = $this->webActionRepo->findIdByCid('TASKS');
        $userTask = new UserTask();

        if (!$createUserTaskDto->getUserTaskState()) {
            throw new ImportantBusinessException(
                $master->getId(),
                $webAction->getId(),
                'Для задачи не указано состояние'
            );
        }

        if (!$createUserTaskDto->getUserTaskType()) {
            throw new ImportantBusinessException(
                $master->getId(),
                $webAction->getId(),
                'Для задачи не указан тип'
            );
        }

        $userTask->setUser($createUserTaskDto->getUser());
        $userTask->setComment($createUserTaskDto->getFreezeReason()->getName());
        $userTask->setCreatedAt(new \DateTime());
        $userTask->setStartTime($createUserTaskDto->getStartDate());
        $userTask->setAuthor($master->getWebUser());
        $userTask->setState($createUserTaskDto->getUserTaskState());
        $userTask->setType($createUserTaskDto->getUserTaskType());

        return  $this->userTaskRepo->save($userTask);
    }

    public function updateUserTaskForCancel(UserTask $userTask): UserTask
    {
        //TODO сделать логирование отмены задачи
        $userTask->setState($this->taskStateRepo->findOneBy(['code' => 'cancelled']));
        return $this->userTaskRepo->save($userTask);
    }
}
