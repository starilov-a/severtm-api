<?php

namespace App\Modules\UserCabinet\UseCase\Tariff;

use App\Modules\Common\Domain\Contexts\Definitions\Tariff\TariffContext;
use App\Modules\Common\Domain\Entity\Tariff;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Repository\WebActionRepository;
use App\Modules\Common\Domain\Rules\Chains\Tariff\ClientChangeTariffRuleChain;
use App\Modules\Common\Domain\Workflow\Tariff\ChangeNextTariffWorkflow;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;

class ClientChangeNextTariffUseCase
{
    public function __construct(
        protected UserRepository                $userRepo,
        protected WebActionRepository           $webActionRepo,

        protected LoggerService                 $loggerService,

        protected ChangeNextTariffWorkflow      $changeNextTariffWorkflow,

        protected ClientChangeTariffRuleChain   $clientChangeTariffRuleChain,
    ) {}

    /**
     * Workflow: Самостоятельно изменение тарифа клиентом
     *
     */
    public function handle(User $client, Tariff $newNextTariff): bool
    {
        //TODO: сделать собственный экшен "Изменение тарифа самим клиентом"
        $webAction = $this->webActionRepo->findIdByCid('WA_USERS_CHANGE_TARIFFS');
        $master = $this->userRepo->find(UserSessionService::getUserId());

        // 1 Бизнес логика конкретного useCase
        $this->clientChangeTariffRuleChain->checkAll(new TariffContext(
            $webAction,
            $master,
            $newNextTariff,
        ));

        // 2 Подвязка нового тарифа
        $this->changeNextTariffWorkflow->handle($client, $newNextTariff);

        // 3 Запись истории
        $this->loggerService->businessLog(new BusinessLogDto(
            $client->getId(),
            $webAction->getId(),
            'Пользователь ' . $client->getId() . ' успешно сменил тариф - ' . $newNextTariff->getName() . '('. $newNextTariff->getId() .')' ,
            true)
        );

        return true;
    }
}