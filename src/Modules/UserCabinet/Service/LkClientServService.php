<?php

namespace App\Modules\UserCabinet\Service;

use App\Modules\Common\Domain\Repository\ProdServModeRepository;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Repository\UserServModeRepository;
use App\Modules\Common\Domain\Service\Dto\Request\OptionsUserServModeDto;
use App\Modules\Common\Domain\Service\Orchestrator\UserServModeOrchestrator;
use App\Modules\Common\Domain\Service\UserServModeService;
use App\Modules\Common\Domain\Service\UserServService;
use App\Modules\Common\Infrastructure\Exception\BusinessException;
use Doctrine\ORM\EntityManagerInterface;

class LkClientServService
{

    public function __construct(
        protected EntityManagerInterface   $em,

        protected UserRepository           $userRepo,
        protected ProdServModeRepository   $prodServModeRepo,
        protected UserServModeRepository   $userServModeRepo,

        protected UserServService          $clientServService,
        protected UserServModeService      $userServModeService,
        protected UserServModeOrchestrator $userServModeOrchestratorService,

    ){}

    public function listAvailableServices(): array
    {
        $services = $this->clientServService->listAvailableServicesWithModes();

        return array_map(function ($serv) {
            return [
                'id' => $serv->getId(),
                'name' => $serv->getName(),
                'code' =>$serv->getStrCode(),
                'modes' => array_map(function ($mode) {
                    return [
                        'id' => $mode->getId(),
                        'name' => $mode->getName(),
                        'code' =>$mode->getStrCode()
                    ];
                },  $serv->getModes()),
            ];
        }, $services);
    }

    public function getCurrentServices(int $uid): array
    {
        $user = $this->userRepo->find($uid);
        $currentServs = $this->userServModeService->getCurrentServiceWithModes($user);

        return array_map(function ($serv) {
            return [
                'id' => $serv->getId(),
                'name' => $serv->getName(),
                'code' =>$serv->getStrCode(),
                'type' => 'ProductService',
                'modes' => array_map(function ($userMode) {
                    return [
                        'usmid' => $userMode->getId(),
                        'name' => $userMode->getMode()->getName(),
                        'code' => $userMode->getMode()->getStrCode(),
                        'type' => 'UserServMode',
                    ];
                },  $serv->getUserModes()),
            ];
        }, $currentServs);
    }

    /*
    * Активация услуги клиентом
    * */
    public function enableService(int $uid, int $modeId): bool
    {
        return $this->em->getConnection()->transactional(function () use (
            $uid,
            $modeId,
        ) {
            $user = $this->userRepo->find($uid);
            $prodServMode = $this->prodServModeRepo->find($modeId);
            $options = new OptionsUserServModeDto();

            //Добавим комментарий, что пользователь сам активировал услугу
            $options->setComment('Активация услуги через личный кабинет');

            $this->userServModeOrchestratorService->addCurrentServiceMode($user, $prodServMode, $options);

            return true;
        });
    }

    /*
     * Отключение услуги клиентом
     * */
    public function disableService(int $uid, int $userModeId): bool
    {
        return $this->em->getConnection()->transactional(function () use (
            $uid,
            $userModeId,
        ) {
            $userServMode = $this->userServModeRepo->findOneBy(['id' => $userModeId, 'user' =>  $this->userRepo->find($uid)]);

            if (!$userServMode)
                throw new BusinessException('Эта услуга не привязана к вашему договору.');

            $this->userServModeOrchestratorService->disableServiceMode($userServMode);

            return false;
        });
    }
}
