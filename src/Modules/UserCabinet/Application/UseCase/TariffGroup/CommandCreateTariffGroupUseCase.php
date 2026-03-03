<?php

namespace App\Modules\Console\UseCase\TariffGroup;

use App\Modules\Common\Domain\Entity\TariffGroup;
use App\Modules\Common\Domain\Service\TariffGroupService;

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
