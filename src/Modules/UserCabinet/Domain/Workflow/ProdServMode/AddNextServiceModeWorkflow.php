<?php

namespace App\Modules\UserCabinet\Domain\Workflow\ProdServMode;

use App\Modules\UserCabinet\Domain\Entity\ProdServMode;
use App\Modules\UserCabinet\Domain\Entity\User;
use App\Modules\UserCabinet\Domain\RepositoryInterface\FinPeriodRepositoryInterface;
use App\Modules\UserCabinet\Domain\Service\Dto\Request\OptionsUserServModeDto;
use App\Modules\UserCabinet\Domain\Service\UserServModeService;

class AddNextServiceModeWorkflow
{
    public function __construct(
        protected FinPeriodRepositoryInterface $finPeriodRepo,
        protected UserServModeService $userServModeService,
    ) {}

    /**
     * Workflow: Добавление опции на следующий месяц
     *
     * 1. Добавление опций
     *
     * @param User $user
     * @param ProdServMode $mode
     * @param OptionsUserServModeDto $optionsUserServModeDto
     * @return void
     */

    public function handle(
        User $user,
        ProdServMode $mode,
        OptionsUserServModeDto $optionsUserServModeDto = new OptionsUserServModeDto
    ): void
    {
        $optionsUserServModeDto->setFinPeriod($this->finPeriodRepo->getNext());
        $this->userServModeService->addServiceMode($user, $mode, $optionsUserServModeDto);
    }
}