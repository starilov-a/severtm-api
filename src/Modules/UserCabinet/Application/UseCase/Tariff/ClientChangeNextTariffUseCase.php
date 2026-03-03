<?php

namespace App\Modules\UserCabinet\Application\UseCase\Tariff;

use App\Modules\UserCabinet\Domain\Contexts\Definitions\Tariff\TariffContext;
use App\Modules\UserCabinet\Domain\Entity\Tariff;
use App\Modules\UserCabinet\Domain\Entity\User;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\WebActionRepositoryInterface;
use App\Modules\UserCabinet\Domain\Rules\Chains\Tariff\ClientChangeTariffRuleChain;
use App\Modules\UserCabinet\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\UserCabinet\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\UserCabinet\Infrastructure\Service\Logger\LoggerService;

class ClientChangeNextTariffUseCase
{
    public function __construct(
        protected UserRepositoryInterface      $userRepo,
        protected WebActionRepositoryInterface $webActionRepo,

        protected LoggerService                $loggerService,

        protected ChangeNextTariffUseCase      $changeNextTariffWorkflow,

        protected ClientChangeTariffRuleChain  $clientChangeTariffRuleChain,
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