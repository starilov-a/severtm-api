<?php

namespace App\Modules\UserCabinet\Application\UseCase\TariffGroup;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\TariffGroup;
use App\Modules\UserCabinet\Domain\Service\TariffGroupService;

class CommandCreateTariffGroupUseCase
{
    public function __construct(
        protected TariffGroupService $tariffGroupService,
    ) {}

    public function handle(string $code, string $name, bool $userVisible = true): TariffGroup
    {
        return $this->tariffGroupService->createTariffGroup($code, $name, $userVisible);
    }
}
