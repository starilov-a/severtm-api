<?php

namespace App\Modules\JurManagerCabinet\Domain\Service;

use App\Modules\JurManagerCabinet\Domain\Entity\Contract;
use App\Modules\JurManagerCabinet\Domain\Entity\ContractReissueSettings;
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
        $this->contractSettingsRepository->applyForReissue($newContract, $settings);
        // 1. Сетевые настройки
        // 2. Параметры пользователя
        // 3. Перенос услуги и тарифов
    }

    public function scheduleReissue(Contract $contract, ContractReissueSettings $settings): void
    {
        // 1. Создаем задачу на Переоформление
    }
}