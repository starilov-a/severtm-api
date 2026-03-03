<?php

namespace App\Modules\UserCabinet\Application\UseCase\Freeze;

use App\Modules\UserCabinet\Domain\Entity\FreezeReason;
use App\Modules\UserCabinet\Domain\Service\FreezeService;

class GetReasonForFreezeUseCase
{
    public function __construct(
        protected FreezeService $freezeService,
    ) {}

    public function handle(): array
    {
        $reasons = $this->freezeService->getClientReasonForFreeze();

        return array_map(static function (FreezeReason $reason): array {
            return [
                'id' => $reason->getId(),
                'name' => $reason->getName(),
            ];
        }, $reasons);
    }
}
