<?php

namespace App\Modules\JurManagerCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserTask;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserTaskParameter;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\CustomerInnRepository;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\UserRepository;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\UserTaskParameterRepository;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\UserTaskRepository;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\UserTaskStateRepository;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\UserTaskTypeRepository;
use App\Modules\JurManagerCabinet\Domain\Entity\Contract\Contract;
use App\Modules\JurManagerCabinet\Domain\Entity\Reissue\ContractReissueProcess;
use App\Modules\JurManagerCabinet\Domain\Entity\Task\TaskState;
use App\Modules\JurManagerCabinet\Domain\Entity\Task\TaskType;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\ContractReissueProcessRepositoryInterface;
use App\Modules\JurManagerCabinet\Infrastructure\Persistence\Doctrine\Repository\Mappers\ContractReissueProcessMapper;

class ContractReissueProcessRepository implements ContractReissueProcessRepositoryInterface
{
    public function __construct(
        protected UserRepository $userRepo,
        protected UserTaskRepository $userTaskRepo,
        protected UserTaskTypeRepository $userTaskTypeRepo,
        protected UserTaskStateRepository $userTaskStateRepo,
        protected UserTaskParameterRepository $userTaskParameterRepo,
        protected CustomerInnRepository $customerInnRepo,
    ) {}

    public function getReissueProcessByContract(Contract $contract): ContractReissueProcess
    {

        $tableUserTaskType = $this->userTaskTypeRepo->findOneBy(['code' => TaskType::CONTRACT_REISSUE]);
        $tableUser = $this->userRepo->find($contract->getId());

        $tableUserTask = $this->userTaskRepo->findOneBy(
            ['type' => $tableUserTaskType, 'user' => $tableUser],
            ['createdAt' => 'DESC']
        );

        if ($tableUserTask === null) {
            throw new \DomainException('Отсутствует задача на переоформление #' . $contract->getId());
        }

        $tableTaskParams = $this->collectTaskParams($tableUserTask);

        return ContractReissueProcessMapper::map($tableUserTask, $tableTaskParams);
    }

    public function issetScheduledByContract(Contract $contract): bool
    {
        $tableUser = $this->userRepo->find($contract->getId());
        $tableUserTaskType = $this->userTaskTypeRepo->findOneBy(['code' => TaskType::CONTRACT_REISSUE]);
        $tableUserTaskState = $this->userTaskStateRepo->findOneBy(['code' => TaskState::NEW]);

        return $this->userTaskRepo->hasTaskWithState($tableUser, $tableUserTaskType, $tableUserTaskState);
    }

    /**
     * @return array<string, string|null>
     */
    private function collectTaskParams(UserTask $task): array
    {
        $params = $this->userTaskParameterRepo->findBy(['task' => $task]);
        $mapped = [];

        /* @var UserTaskParameter $param */
        foreach ($params as $param) {
            $mapped[$param->getType()->getCode()] = $param->getValue();
        }

        return $mapped;
    }

}
