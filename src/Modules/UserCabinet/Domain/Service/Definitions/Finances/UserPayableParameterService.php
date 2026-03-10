<?php

namespace App\Modules\UserCabinet\Domain\Service\Definitions\Finances;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\Device;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserPayable;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserPayableParameter;
use App\Modules\UserCabinet\Domain\RepositoryInterface\EnumParameterRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserPayableParameterRepositoryInterface;

class UserPayableParameterService
{
    public function __construct(
        protected UserPayableParameterRepositoryInterface $userPayableParameterRepo,
        protected EnumParameterRepositoryInterface $enumParameterRepo
    ){}

    public function addLinkToDevice(UserPayable $userPayable, Device $device)
    {
        $userPayableParameter = new UserPayableParameter;

        $userPayableParameter->setUserPayable($userPayable);
        $userPayableParameter->setParameter($this->enumParameterRepo->findOneBy(['code' => 'device_id']));
        $userPayableParameter->setValue($device->getId());

        return $this->userPayableParameterRepo->save($userPayableParameter);
    }
}
