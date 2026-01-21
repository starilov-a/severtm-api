<?php

namespace App\Modules\Common\Application\UseCase\TariffGroup;

use App\Modules\Common\Domain\Entity\TariffGroup;
use App\Modules\Common\Domain\Repository\TariffGroupRepository;
use App\Modules\Common\Domain\Service\TariffGroupService;
use App\Modules\Common\Infrastructure\Exception\BusinessException;

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
