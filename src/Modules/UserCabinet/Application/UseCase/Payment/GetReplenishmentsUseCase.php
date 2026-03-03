<?php

namespace App\Modules\UserCabinet\Application\UseCase\Payment;

use App\Modules\UserCabinet\Application\Dto\Response\ReplenishmentDto;
use App\Modules\UserCabinet\Application\Dto\Response\ReplenishmentsCollectionDto;
use App\Modules\UserCabinet\Domain\RepositoryInterface\ReplenishmentRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;
use App\Modules\UserCabinet\Domain\Service\Dto\Request\FilterDto;

class GetReplenishmentsUseCase
{
    public function __construct(
        protected ReplenishmentRepositoryInterface $replenishmentRepo,
        protected UserRepositoryInterface $userRepo,
    ) {}

    public function handle(int $uid, FilterDto $filter): ReplenishmentsCollectionDto
    {
        $user = $this->userRepo->find($uid);
        $replenishments = $this->replenishmentRepo->findByUser($user, $filter);

        $dtoCollection = new ReplenishmentsCollectionDto();
        foreach ($replenishments as $replenishment) {
            $dtoCollection->add(new ReplenishmentDto($replenishment));
        }

        return $dtoCollection;
    }
}
