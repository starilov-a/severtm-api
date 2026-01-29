<?php

namespace App\Modules\Common\Domain\Workflow\ProdServMode;

use App\Modules\Common\Domain\Entity\ProdServMode;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Repository\FinPeriodRepository;
use App\Modules\Common\Domain\Service\Dto\Request\OptionsUserServModeDto;
use App\Modules\Common\Domain\Service\UserServModeService;

class AddNextServiceModeWorkflow
{
    public function __construct(
        protected FinPeriodRepository $finPeriodRepo,
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