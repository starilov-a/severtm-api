<?php

namespace App\Modules\UserCabinet\Domain\Service\Definitions\Finances;

use App\Modules\UserCabinet\Domain\Entity\Device;
use App\Modules\UserCabinet\Domain\Entity\UserPayable;
use App\Modules\UserCabinet\Domain\Entity\UserPayableParameter;
use App\Modules\UserCabinet\Domain\Persistence\UnitOfWorkInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\EnumParameterRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserPayableParameterRepositoryInterface;

class UserPayableParameterService
{
    public function __construct(
        protected UnitOfWorkInterface $uow,
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
