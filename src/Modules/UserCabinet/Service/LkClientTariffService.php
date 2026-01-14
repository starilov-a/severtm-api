<?php

namespace App\Modules\UserCabinet\Service;

use App\Modules\Common\Application\UseCase\Tariff\ChangeNextTariffUseCase;
use App\Modules\Common\Domain\Repository\TariffRepository;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Repository\WebActionRepository;
use App\Modules\Common\Domain\Service\Rules\Chains\Tariff\ClientChangeTariffRuleChain;
use App\Modules\Common\Domain\Service\Rules\Contexts\TariffContext;
use App\Modules\Common\Domain\Service\TariffService;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;
use App\Modules\UserCabinet\Service\Dto\Response\TariffDto;
use Doctrine\ORM\EntityManagerInterface;

class LkClientTariffService
{
    public function __construct(
        protected TariffRepository       $tariffRepo,
        protected UserRepository         $userRepo,
        protected WebActionRepository    $webActionRepo,

        protected TariffService          $tariffService,
        protected LoggerService          $loggerService,

        protected ChangeNextTariffUseCase $changeNextTariffUseCase,

        protected ClientChangeTariffRuleChain $clientChangeTariffRuleChain,

        protected EntityManagerInterface $em,
    ) {}
    public function getCurrentTariff(int $uid): TariffDto
    {
        $user = $this->userRepo->find($uid);
        $currentTariff = $user->getCurrentTariff();

        return new TariffDto(
            $currentTariff->getId(),
            $currentTariff->getName(),
            $currentTariff->getPrice(),
            !($currentTariff->getId() === 1)
        );
    }

    // Изменение тарифа на след месяц
    public function changeNextTariff(int $uid, int $newTariffId): bool
    {
        return $this->em->getConnection()->transactional(function () use (
            $uid,
            $newTariffId,
        ) {
            //TODO: сделать собственный экшен "Изменение тарифа самим клиентом"
            $client = $this->userRepo->find($uid);
            $webAction = $this->webActionRepo->findIdByCid('WA_USERS_CHANGE_TARIFFS');
            $newNextTariff = $this->tariffRepo->find($newTariffId);
            $master = $this->userRepo->find(UserSessionService::getUserId());

            // 1 Бизнес логика слоя ЛК
            $this->clientChangeTariffRuleChain->checkAll(new TariffContext(
                $webAction,
                $master,
                $newNextTariff,
            ));

            // 2 Подвязка нового тарифа
            $this->changeNextTariffUseCase->handle($client, $newNextTariff);

            // 3 Запись истории
            $this->loggerService->businessLog(new BusinessLogDto(
                $uid,
                $webAction->getId(),
                'Пользователь ' . $uid . ' успешно сменил тариф - ' . $newNextTariff->getName() . '('. $newTariffId .')' ,
                true)
            );

            return true;
        });
    }


    public function getAvailableTariffs(int $uid): array
    {
        $client = $this->userRepo->find($uid);
        $dto = new \App\Modules\Common\Domain\Service\Dto\Request\TariffFilterDto();

        //1. Тарифы стоят больше чем текущий
        $currentTariff = $client->getCurrentTariff();
        $dto->setMinPrice($currentTariff->getPrice());

        //TODO: перенести в RULE
        //2. Тариф доступен для изменения:
        $dto->addGroupCodes('canBeChangeByClient');
        //3. Тариф имеет группу, обозначающая необходимый регион
        array_map(function ($region) use ($dto) {
            $dto->addRegionGroupCodes($region);
        }, [
            1 => 'velikij_novgorod_tariffs',
            2 => 'cherepevets_tariffs',
            3 => 'chelyzbinsk_tariffs',
            4 => 'yaroslavl_tariffs'
        ]);



        $tariffs = $this->tariffService->getTariffsForClient($client, $dto);

        return array_map(function ($tariff) {
            return [
                'id' => $tariff->getId(),
                'name' => $tariff->getName(),
                'price' => $tariff->getPrice()
            ];
        }, $tariffs);
    }
}
