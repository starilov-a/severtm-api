<?php

namespace App\Modules\JurManagerCabinet\Domain\Service;

use App\Modules\JurManagerCabinet\Domain\Entity\Contract\Contract;
use App\Modules\JurManagerCabinet\Domain\Entity\Reissue\ContractReissueSettings;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\ContractRepositoryInterface;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\ContractSettingsRepositoryInterface;

class ContractReissueService
{
    public function __construct(
        private readonly ContractSettingsRepositoryInterface    $contractSettingsRepository,
        protected ContractRepositoryInterface                   $contractRepo,
    ) {}

    public function collectSettings(Contract $oldContract): ContractReissueSettings
    {
        return $this->contractSettingsRepository->loadForReissue($oldContract);
    }

    public function transferSettings(Contract $newContract, ContractReissueSettings $settings): void
    {
        // Переносим только критичные установки (сетевые)
        $this->contractSettingsRepository->applyForReissue($newContract, $settings);
    }
}