<?php

namespace App\Modules\JurManagerCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserTaskParameter;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\EnumTaskRepository;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\UserRepository;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\UserTaskParameterRepository;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\UserTaskRepository;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\UserTaskTypeRepository;
use App\Modules\JurManagerCabinet\Domain\Entity\Contract\Contract;
use App\Modules\JurManagerCabinet\Domain\Entity\Reissue\ContractReissueProcess;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\ContractReissueProcessRepositoryInterface;
use App\Modules\JurManagerCabinet\Infrastructure\Persistence\Doctrine\Repository\Mappers\ContractReissueProcessMapper;

class ContractReissueProcessRepository implements ContractReissueProcessRepositoryInterface
{
    public function __construct(
        protected UserRepository $userRepo,
        protected UserTaskRepository $userTaskRepo,
        protected UserTaskTypeRepository $userTaskTypeRepo,
        protected UserTaskParameterRepository $userTaskParameterRepo
    ) {}

    public function getReissueProcessByContract(Contract $contract): ContractReissueProcess
    {

        $tableUserTaskType = $this->userTaskTypeRepo->findOneBy(['str_code' => 'on_reissue']);
        $tableUser = $this->userRepo->find($contract->getId());

        $tableUserTask = $this->userTaskRepo->findOneBy(['type' => $tableUserTaskType, 'uid' => $tableUser], ['created_at' => 'DESC']);

        $tableTaskParams = [];

        /* @var UserTaskParameter $param */
        $param = $this->userTaskParameterRepo->findOneBy(['type' => 'reissue_inn']);
        $tableTaskParams[$param->getType()->getCode()] = $param->getValue();

        /* @var UserTaskParameter $param */
        $param = $this->userTaskParameterRepo->findOneBy(['type' => 'reissue_fio']);
        $tableTaskParams[$param->getType()->getCode()] = $param->getValue();

        /* @var UserTaskParameter $param */
        $param = $this->userTaskParameterRepo->findOneBy(['type' => 'reissue_login']);
        $tableTaskParams[$param->getType()->getCode()] = $param->getValue();

        /* @var UserTaskParameter $param */
        $param = $this->userTaskParameterRepo->findOneBy(['type' => 'reissue_phone']);
        $tableTaskParams[$param->getType()->getCode()] = $param->getValue();

        /* @var UserTaskParameter $param */
        $param = $this->userTaskParameterRepo->findOneBy(['type' => 'reissue_comment']);
        $tableTaskParams[$param->getType()->getCode()] = $param->getValue();

        return ContractReissueProcessMapper::map($tableUserTask, $tableTaskParams);
    }

    public function findScheduledByContract(Contract $contract): ContractReissueProcess
    {
        // TODO: Implement findScheduledByContract() method.
    }
}