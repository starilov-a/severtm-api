<?php

namespace App\Modules\Common\Application\UseCase\TariffGroup;

use App\Modules\Common\Domain\Entity\Tariff;
use App\Modules\Common\Domain\Entity\TariffGroup;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Repository\WebActionRepository;
use App\Modules\Common\Domain\Service\TariffGroupService;
use App\Modules\Common\Domain\Service\TariffService;
use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;

class CommandLinkTariffForGroupUseCase
{
    public function __construct(
        protected WebActionRepository $webActionRepo,
        protected UserRepository $userRepo,

        protected LoggerService $loggerService,
        protected TariffService $tariffService,
        protected TariffGroupService $tariffGroupService,
    ) {}
    public function handle(Tariff $tariff, TariffGroup $tariffGroup): bool
    {
        // 1. Добавляем бизнес-проверки проверки

        // 2. Добавляем
        $tariff = $this->tariffGroupService->linkTariffForGroup($tariff, $tariffGroup);
        $this->tariffService->save($tariff);

        // 4. запись в историю об успехе

        return true;
    }
}
