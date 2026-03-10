<?php

namespace App\Modules\UserCabinet\Application\UseCase\ProdServMode;

use App\Modules\UserCabinet\Domain\Dto\Request\OptionsUserServModeDto;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\ProdServMode;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;
use App\Modules\UserCabinet\Domain\RepositoryInterface\FinPeriodRepositoryInterface;
use App\Modules\UserCabinet\Domain\Service\UserServModeService;

class AddNextServiceModeUseCase
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