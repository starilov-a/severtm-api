<?php

namespace App\Modules\UserCabinet\Application\UseCase\Payment;

use App\Modules\UserCabinet\Application\Dto\Response\ReplenishmentDto;
use App\Modules\UserCabinet\Application\Dto\Response\ReplenishmentsCollectionDto;
use App\Modules\UserCabinet\Domain\Repository\ReplenishmentRepository;
use App\Modules\UserCabinet\Domain\Repository\UserRepository;
use App\Modules\UserCabinet\Domain\Service\Dto\Request\FilterDto;

class GetReplenishmentsUseCase
{
    public function __construct(
        protected ReplenishmentRepository $replenishmentRepo,
        protected UserRepository $userRepo,
    ) {}

    public function handle(int $uid, FilterDto $filter): ReplenishmentsCollectionDto
    {
        $replenishments = $this->replenishmentRepo->findBy(
            ['user' => $this->userRepo->find($uid)],
            ['dateTs' => 'DESC'],
            $filter->getLimit(),
            $filter->getOffset()
        );

        $dtoCollection = new ReplenishmentsCollectionDto();
        foreach ($replenishments as $replenishment) {
            $dtoCollection->add(new ReplenishmentDto($replenishment));
        }

        return $dtoCollection;
    }
}
