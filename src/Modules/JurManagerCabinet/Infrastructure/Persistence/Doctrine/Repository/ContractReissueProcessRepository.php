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
use App\Modules\JurManagerCabinet\Application\Dto\Request\Reissue\ReissueContractDto;
use App\Modules\JurManagerCabinet\Domain\Entity\Contract\Contract;
use App\Modules\JurManagerCabinet\Domain\Entity\Reissue\ContractReissueStatus;
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
        $newContractId = $this->resolveNewContractId($tableTaskParams);

        return ContractReissueProcessMapper::map($tableUserTask, $tableTaskParams, $newContractId);
    }

    public function findScheduledByContract(Contract $contract): ContractReissueProcess
    {
        $tableUserTaskType = $this->userTaskTypeRepo->findOneBy(['code' => TaskType::CONTRACT_REISSUE]);
        $tableUser = $this->userRepo->find($contract->getId());

        $states = array_filter([
            $this->userTaskStateRepo->findOneBy(['code' => TaskState::NEW]),
            $this->userTaskStateRepo->findOneBy(['code' => TaskState::DEFERRED]),
        ]);

        $qb = $this->userTaskRepo->createQueryBuilder('ut')
            ->andWhere('ut.user = :user')->setParameter('user', $tableUser)
            ->andWhere('ut.type = :type')->setParameter('type', $tableUserTaskType);

        if ($states !== []) {
            $qb->andWhere('ut.state IN (:states)')->setParameter('states', $states);
        }

        $tableUserTask = $qb
            ->orderBy('ut.createdAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if ($tableUserTask === null) {
            return $this->buildEmptyProcess($contract);
        }

        $tableTaskParams = $this->collectTaskParams($tableUserTask);
        $newContractId = $this->resolveNewContractId($tableTaskParams);

        return ContractReissueProcessMapper::map($tableUserTask, $tableTaskParams, $newContractId);
    }

    /**
     * @return array<string, string|null>
     */
    private function collectTaskParams(UserTask $task): array
    {
        $params = $this->userTaskParameterRepo->findBy(['task' => $task->getId()]);
        $mapped = [];

        /* @var UserTaskParameter $param */
        foreach ($params as $param) {
            $mapped[$param->getType()->getCode()] = $param->getValue();
        }

        return $mapped;
    }

    /**
     * Ищем либо по login либо по inn
     * TODO: Сделать ссылку на uid договора в новом договоре и в старом
     */
    private function resolveNewContractId(array $taskParams): int
    {
        $login = $taskParams['reissue_login'] ?? null;
        if ($login !== null && $login !== '') {
            $user = $this->userRepo->findOneBy(['login' => $login]);
            if ($user !== null) {
                return $user->getId();
            }
        }

        $inn = $taskParams['reissue_inn'] ?? null;
        if ($inn !== null && $inn !== '') {
            $customerInn = $this->customerInnRepo->findOneBy(['inn' => $inn]);
            if ($customerInn !== null) {
                $user = $this->userRepo->findOneBy(['customerInn' => $customerInn]);
                if ($user !== null) {
                    return $user->getId();
                }
            }
        }

        throw new \DomainException('New contract not found for reissue process');
    }

    private function buildEmptyProcess(Contract $contract): ContractReissueProcess
    {
        $now = new \DateTimeImmutable();

        $dto = new ReissueContractDto(
            contractId: $contract->getId(),
            managerId: 0,
            newInn: (int) $contract->getInn(),
            dateReissue: $now,
            fio: $contract->getFullName(),
            login: $contract->getLogin(),
            password: '',
            phone: $contract->getPhone(),
            comment: ''
        );

        return new ContractReissueProcess(
            oldContractId: $contract->getId(),
            newContractId: $contract->getId(),
            contractReissueDto: $dto,
            scheduleAt: $now,
            status: ContractReissueStatus::FAILED
        );
    }
}
