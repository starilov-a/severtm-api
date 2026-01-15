<?php

namespace App\Modules\Common\Application\UseCase\ProdServMode;

use App\Modules\Common\Domain\Entity\ProdServMode;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Repository\FinPeriodRepository;
use App\Modules\Common\Domain\Service\Definitions\Finances\UserPaymentsService;
use App\Modules\Common\Domain\Service\Dto\Request\OptionsUserServModeDto;
use App\Modules\Common\Domain\Service\Rules\Contexts\OnlyUserContext;
use App\Modules\Common\Domain\Service\Rules\Definitions\User\UserIsNotNotActivatedRule;
use App\Modules\Common\Domain\Service\UserServModeService;
use Doctrine\ORM\EntityManagerInterface;

class AddCurrentServiceModeUseCase
{
    public function __construct(
        protected EntityManagerInterface $em,

        protected FinPeriodRepository       $finPeriodRepo,

        protected UserServModeService       $userServModeService,
        protected UserPaymentsService       $paymentsService,

        protected UserIsNotNotActivatedRule $userIsNotNotActivatedRule,
    ) {}
    /**
     * UseCase: Добавление опции на текущий месяц
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