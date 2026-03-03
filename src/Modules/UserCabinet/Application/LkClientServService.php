<?php

namespace App\Modules\UserCabinet\Application;

use App\Modules\Common\Domain\Repository\ProdServModeRepository;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Repository\UserServModeRepository;
use App\Modules\Common\Domain\Service\UserServModeService;
use App\Modules\Common\Domain\Service\UserServService;
use App\Modules\UserCabinet\Application\UseCase\ProdServMode\DisableServiceModeUseCase;
use App\Modules\UserCabinet\Infrastructure\Exception\BusinessException;
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

        protected DisableServiceModeUseCase  $disableServiceModeUseCase,
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

            $this->disableServiceModeUseCase->handle($userServMode);

            return false;
        });
    }
}
