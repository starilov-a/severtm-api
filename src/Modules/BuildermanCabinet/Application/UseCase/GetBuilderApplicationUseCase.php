<?php

namespace App\Modules\BuildermanCabinet\Application\UseCase;

use App\Modules\BuildermanCabinet\Domain\Entity\Application;
use App\Modules\BuildermanCabinet\Domain\RepositoryInterface\ApplicationRepositoryInterface;
use App\Modules\BuildermanCabinet\Domain\RepositoryInterface\BuilderRepositoryInterface;
use App\Modules\BuildermanCabinet\Domain\Service\ApplicationStatusService;

class GetBuilderApplicationUseCase
{
    public function __construct(
        protected ApplicationRepositoryInterface $applicationRepo,
        protected BuilderRepositoryInterface $builderRepo,
        protected ApplicationStatusService $applicationStatusService
    ) {}

    /**
     * @return array<Application>
     */
    public function execute(int $builderId): array
    {
        // Получение сущностей, с которыми будет работать
        $builder = $this->builderRepo->findById($builderId);
        $availableStatuses = $this->applicationStatusService->getStatusesForBuilderList();

        //получение целевой сущности
        $applicationList = $this->applicationRepo->findActiveAppListByBuilder($builder, $availableStatuses);

        return $applicationList;
    }
}