<?php

namespace App\Modules\UserCabinet\Application\UseCase\ProdServMode;

use App\Modules\UserCabinet\Domain\Contexts\Definitions\User\OnlyUserContext;
use App\Modules\UserCabinet\Domain\Dto\Request\OptionsUserServModeDto;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\ProdServMode;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;
use App\Modules\UserCabinet\Domain\RepositoryInterface\FinPeriodRepositoryInterface;
use App\Modules\UserCabinet\Domain\Rules\Definitions\User\UserIsNotNotActivatedRule;
use App\Modules\UserCabinet\Domain\Service\Definitions\Finances\UserPaymentsService;
use App\Modules\UserCabinet\Domain\Service\UserServModeService;

class AddCurrentServiceModeUseCase
{
    public function __construct(
        protected FinPeriodRepositoryInterface       $finPeriodRepo,

        protected UserServModeService       $userServModeService,
        protected UserPaymentsService       $paymentsService,

        protected UserIsNotNotActivatedRule $userIsNotNotActivatedRule,
    ) {}
    /**
     * Workflow: Добавление опции на текущий месяц
     *
     * 1. Добавление опций
     * 2. Списание (если не неактивированный юрик)
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
        // 1. Добавление usm
        $optionsUserServModeDto->setFinPeriod($this->finPeriodRepo->getCurrent());
        $userServMode = $this->userServModeService->addServiceMode($user, $mode, $optionsUserServModeDto);

        // 2. Списание денег, если не неактивированный юрик
        if ($this->userIsNotNotActivatedRule->check(new OnlyUserContext($user))->ok)
            $this->paymentsService->paymentsForServiceMode($userServMode);

    }
}
