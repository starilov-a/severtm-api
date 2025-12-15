<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\ProdDiscountTemp;
use App\Modules\Common\Domain\Entity\UserPayable;
use App\Modules\Common\Domain\Repository\ProdDiscountTempRepository;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;

class ProdDiscountTempService
{
    public function __construct(
        protected ProdDiscountTempRepository $discountTempRepo,
        protected UserRepository $userRepo,
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
        $discountTemp->setMaster($this->userRepo->find(UserSessionService::getUserId()));
        $discountTemp->setProdComments($comment);

        $this->save($discountTemp);

        return $discountTemp;
    }

    protected function save(ProdDiscountTemp $discountTemp): ProdDiscountTemp
    {
        $em = $this->discountTempRepo->getEntityManager();
        $em->persist($discountTemp);
        $em->flush();

        return $discountTemp;
    }
}

