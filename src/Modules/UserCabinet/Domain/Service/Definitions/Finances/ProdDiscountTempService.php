<?php

namespace App\Modules\UserCabinet\Domain\Service\Definitions\Finances;

use App\Modules\UserCabinet\Domain\Entity\ProdDiscountTemp;
use App\Modules\UserCabinet\Domain\Entity\UserPayable;
use App\Modules\UserCabinet\Domain\Persistence\UnitOfWorkInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\ProdDiscountTempRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;
use App\Modules\UserCabinet\Infrastructure\Service\Auth\Service\UserSessionService;

class ProdDiscountTempService
{
    public function __construct(
        protected UnitOfWorkInterface $uow,
        protected ProdDiscountTempRepositoryInterface $discountTempRepo,
        protected UserRepositoryInterface $userRepo,
    ) {}


    public function createForAddingMode(
        UserPayable $userPayable,
        string $comment
    ): ProdDiscountTemp
    {
        $discountTemp = new ProdDiscountTemp();

        $discountTemp->setPayable($userPayable);
        $discountTemp->setUser($userPayable->getUser());
        $discountTemp->setProduct($userPayable->getServiceMode()->getService());
        $discountTemp->setModeCost($userPayable->getServiceMode()->getProdServModeCost());
        $discountTemp->setQnt($userPayable->getPayable());
        $discountTemp->setNumber($userPayable->getPayable());
        $discountTemp->setDiscountDate($userPayable->getCreatedAt()->getTimestamp());
        $discountTemp->setMaster($this->userRepo->find(UserSessionService::getUserId())->getLogin());
        $discountTemp->setProdComments($comment);

        $this->discountTempRepo->save($discountTemp);

        return $discountTemp;
    }
}
