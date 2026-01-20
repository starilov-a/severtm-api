<?php

namespace App\Modules\Common\Application\UseCase\Tariff;

use App\Modules\Common\Domain\Contexts\Definitions\Tariff\OnlyAvailableTariffsForClientContext;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Policy\Definitions\Tariff\TariffAvailabilityForClientPolicy;
use App\Modules\Common\Domain\Service\TariffService;

class GetAvailableTariffsForClientUseCase
{
    public function __construct(
        protected TariffService $tariffService,

        protected TariffAvailabilityForClientPolicy $tariffAvailabilityForClientPolicy
    ) {}

    /**
     * UseCase: Получение тарифов доступных для смены клиентом
     *
     */
    public function handle(User $client): array
    {
        // 1. получение тарифов
        $tariffs = $this->tariffService->getTariffsByUser($client);

        // 2. Сортировка по доступным тарифам клиенту
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