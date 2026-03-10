<?php

namespace App\Modules\Starts\Application\UseCase;

use App\Modules\Starts\Domain\Entity\Application;
use App\Modules\Starts\Domain\RepositoryInterface\ApplicationRepositoryInterface;
use App\Modules\Starts\Domain\RepositoryInterface\BuilderRepositoryInterface;
use App\Modules\Starts\Domain\Service\ApplicationStatusService;

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
    public function handle(int $builderId): array
    {
        // Получение сущностей, с которыми будет работать
        $builder = $this->builderRepo->find($builderId);
        $availableStatuses = $this->applicationStatusService->getStatusesForBuilderList();

        //получение целевой сущности
        $applicationList = $this->applicationRepo->findListByBuilder($builder, $availableStatuses);

        return $applicationList;
    }
}