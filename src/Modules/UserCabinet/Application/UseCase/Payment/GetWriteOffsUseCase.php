<?php

namespace App\Modules\UserCabinet\Application\UseCase\Payment;

use App\Modules\UserCabinet\Application\Dto\Response\WriteOffCollectionDto;
use App\Modules\UserCabinet\Application\Dto\Response\WriteOffDto;
use App\Modules\UserCabinet\Domain\Repository\ProdDiscountHistoryRepository;
use App\Modules\UserCabinet\Domain\Repository\UserRepository;
use App\Modules\UserCabinet\Domain\Service\Dto\Request\FilterDto;

class GetWriteOffsUseCase
{
    public function __construct(
        protected ProdDiscountHistoryRepository $writeOffRepo,
        protected UserRepository $userRepo,
    ) {}

    public function handle(int $uid, FilterDto $filter): WriteOffCollectionDto
    {
        $writeOffs = $this->writeOffRepo->findBy(
            ['user' => $this->userRepo->find($uid)],
            ['discountDateTs' => 'DESC'],
            $filter->getLimit(),
            $filter->getOffset()
        );

        $dtoCollection = new WriteOffCollectionDto();
        foreach ($writeOffs as $writeOff) {
            $dtoCollection->add(new WriteOffDto($writeOff));
        }

        return $dtoCollection;
    }
}
