<?php

namespace App\Modules\UserCabinet\Domain\Service;

use App\Modules\UserCabinet\Domain\Entity\UserTask;
use App\Modules\UserCabinet\Domain\Repository\UserRepository;
use App\Modules\UserCabinet\Domain\Repository\UserTaskStateRepository;
use App\Modules\UserCabinet\Domain\Repository\WebActionRepository;
use App\Modules\UserCabinet\Domain\Service\Dto\Request\CreateUserTaskDto;
use App\Modules\UserCabinet\Infrastructure\Exception\ImportantBusinessException;
use App\Modules\UserCabinet\Infrastructure\Service\Auth\Service\UserSessionService;
use Doctrine\ORM\EntityManagerInterface;

class TaskService
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected UserRepository $userRepo,
        protected WebActionRepository $webActionRepo,
        protected UserTaskStateRepository $taskStateRepo,
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

        return  $this->save($userTask);
    }

    public function updateUserTaskForCancel(UserTask $userTask): UserTask
    {
        //TODO сделать логирование отмены задачи
        $userTask->setState($this->taskStateRepo->findOneBy(['code' => 'cancelled']));
        return $this->update($userTask);
    }

    public function update(UserTask $userTask): UserTask
    {
        return $this->save($userTask);
    }

    protected function save(UserTask $userTask): UserTask
    {
        $this->em->persist($userTask);
        $this->em->flush();

        return $userTask;
    }
}