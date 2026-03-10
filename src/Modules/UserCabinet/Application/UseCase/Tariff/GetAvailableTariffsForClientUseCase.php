<?php

namespace App\Modules\UserCabinet\Application\UseCase\Tariff;

use App\Modules\UserCabinet\Domain\Contexts\Definitions\Tariff\OnlyAvailableTariffsForClientContext;
use App\Modules\UserCabinet\Domain\Dto\Request\TariffFilterDto;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;
use App\Modules\UserCabinet\Domain\Policy\Tariff\TariffAvailabilityForClientPolicy;
use App\Modules\UserCabinet\Domain\Service\TariffService;

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