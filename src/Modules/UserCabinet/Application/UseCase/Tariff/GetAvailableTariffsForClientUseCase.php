<?php

namespace App\Modules\UserCabinet\UseCase\Tariff;

use App\Modules\Common\Domain\Contexts\Definitions\Tariff\OnlyAvailableTariffsForClientContext;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Policy\Definitions\Tariff\TariffAvailabilityForClientPolicy;
use App\Modules\Common\Domain\Service\Dto\Request\TariffFilterDto;
use App\Modules\Common\Domain\Service\TariffService;

class GetAvailableTariffsForClientUseCase
{
    public function __construct(
        protected TariffService $tariffService,

        protected TariffAvailabilityForClientPolicy $tariffAvailabilityForClientPolicy
    ) {}

    /**
     * Workflow: Получение тарифов доступных для смены клиентом
     *
     */
    public function handle(User $client): array
    {
        $dto = new TariffFilterDto;

        // 1. Добавление фильтра только для клиентов
        $dto->addRequiredGroupCode('canBeChangeByClient');
        // 2. получение тарифов
        $tariffs = $this->tariffService->getTariffsByUser($client, $dto);

        // 3. Сортировка по доступным тарифам клиенту
        $currentTariff = $client->getCurrentTariff();

        $availableTariffs = [];
        foreach ($tariffs as $tariff) {
            if ($this->tariffAvailabilityForClientPolicy->isAllowed(new OnlyAvailableTariffsForClientContext($tariff, $currentTariff))) {
                $availableTariffs[] = $tariff;
            }
        }

        return $availableTariffs;
    }
}