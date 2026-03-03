<?php

namespace App\Modules\UserCabinet\Application\UseCase\Service;

use App\Modules\UserCabinet\Application\UseCase\ProdServMode\DisableServiceModeUseCase;
use App\Modules\UserCabinet\Domain\Repository\UserRepository;
use App\Modules\UserCabinet\Domain\Repository\UserServModeRepository;
use App\Modules\UserCabinet\Infrastructure\Exception\BusinessException;
use Doctrine\ORM\EntityManagerInterface;

class DisableServiceUseCase
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected UserRepository $userRepo,
        protected UserServModeRepository $userServModeRepo,
        protected DisableServiceModeUseCase $disableServiceModeUseCase,
    ) {}

    public function handle(int $uid, int $userModeId): bool
    {
        return $this->em->getConnection()->transactional(function () use (
            $uid,
            $userModeId,
        ) {
            $userServMode = $this->userServModeRepo->findOneBy([
                'id' => $userModeId,
                'user' => $this->userRepo->find($uid)
            ]);

            if (!$userServMode) {
                throw new BusinessException('Эта услуга не привязана к вашему договору.');
            }

            $this->disableServiceModeUseCase->handle($userServMode);

            return false;
        });
    }
}
