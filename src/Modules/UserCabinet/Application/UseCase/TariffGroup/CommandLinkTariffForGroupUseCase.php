<?php

namespace App\Modules\UserCabinet\Application\UseCase\TariffGroup;

use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;
use App\Modules\UserCabinet\Domain\Entity\Tariff;
use App\Modules\UserCabinet\Domain\Entity\TariffGroup;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\WebActionRepositoryInterface;
use App\Modules\UserCabinet\Domain\Service\TariffGroupService;
use App\Modules\UserCabinet\Domain\Service\TariffService;

class CommandLinkTariffForGroupUseCase
{
    public function __construct(
        protected WebActionRepositoryInterface $webActionRepo,
        protected UserRepositoryInterface $userRepo,

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
