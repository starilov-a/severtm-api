<?php

namespace App\Modules\JurManagerCabinet\Infrastructure\Persistence\Doctrine\Repository\Mappers;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserTask;
use App\Modules\JurManagerCabinet\Application\Dto\Request\Reissue\ReissueContractDto;
use App\Modules\JurManagerCabinet\Domain\Entity\Reissue\ContractReissueProcess;
use App\Modules\JurManagerCabinet\Domain\Entity\Reissue\ContractReissueStatus;
use App\Modules\JurManagerCabinet\Domain\Entity\Task\TaskState;

class ContractReissueProcessMapper
{
    /**
     * @param UserTask $tableUserTask
     * @param array $tableEnumParams
     * @param int $newContractId
     * @return ContractReissueProcess
     */
    static public function map(UserTask $tableUserTask, array $tableEnumParams, int $newContractId): ContractReissueProcess
    {

        $scheduleAt = $tableUserTask->getStartTime() ?? $tableUserTask->getCreatedAt();
        $scheduleAtImmutable = new \DateTimeImmutable($scheduleAt->format('Y-m-d H:i:s'));

        $reissueDto = new ReissueContractDto(
            contractId: $tableUserTask->getUser()->getId(),
            managerId: $tableUserTask->getAuthor()->getUid(),
            newInn: (int) ($tableEnumParams['reissue_inn'] ?? 0),
            dateReissue: $scheduleAtImmutable,
            fio: (string) ($tableEnumParams['reissue_fio'] ?? ''),
            login: (string) ($tableEnumParams['reissue_login'] ?? ''),
            password: '',
            phone: (string) ($tableEnumParams['reissue_phone'] ?? ''),
            comment: (string) ($tableEnumParams['reissue_comment'] ?? '')
        );

        return new ContractReissueProcess(
            oldContractId: $tableUserTask->getUser()->getId(),
            newContractId: $newContractId,
            contractReissueDto: $reissueDto,
            scheduleAt: $scheduleAtImmutable,
            status: self::mapStatus($tableUserTask->getState()->getCode()),
            taskId: $tableUserTask->getId()
        );
    }

    private static function mapStatus(string $taskState): string
    {
        return match ($taskState) {
            TaskState::NEW, TaskState::DEFERRED => ContractReissueStatus::SCHEDULED,
            TaskState::FINISHED => ContractReissueStatus::COMPLETED,
            TaskState::ERROR, TaskState::CANCELLED => ContractReissueStatus::FAILED,
            default => ContractReissueStatus::IN_PROGRESS,
        };
    }
}
