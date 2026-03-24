<?php

namespace App\Modules\UserCabinet\Application\UseCase\Service;

use App\Modules\Common\Infrastructure\Exception\BusinessException;
use App\Modules\UserCabinet\Application\UseCase\ProdServMode\DisableServiceModeUseCase;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserServModeRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class DisableServiceUseCase
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected UserRepositoryInterface $userRepo,
        protected UserServModeRepositoryInterface $userServModeRepo,
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
