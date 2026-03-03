<?php

namespace App\Modules\UserCabinet\Domain\Service\Definitions\Finances;

use App\Modules\UserCabinet\Domain\Entity\Device;
use App\Modules\UserCabinet\Domain\Entity\UserPayable;
use App\Modules\UserCabinet\Domain\Entity\UserPayableParameter;
use App\Modules\UserCabinet\Domain\Repository\EnumParameterRepository;
use App\Modules\UserCabinet\Domain\Repository\UserPayableParameterRepository;

class UserPayableParameterService
{
    public function __construct(
        protected UserPayableParameterRepository $userPayableParameterRepo,
        protected EnumParameterRepository $enumParameterRepo
    ){}

    public function addLinkToDevice(UserPayable $userPayable, Device $device)
    {
        $userPayableParameter = new UserPayableParameter;

        $userPayableParameter->setUserPayable($userPayable);
        $userPayableParameter->setParameter($this->enumParameterRepo->findOneBy(['code' => 'device_id']));
        $userPayableParameter->setValue($device->getId());

        return $this->save($userPayableParameter);
    }

    protected function save(UserPayableParameter $userPayableParameter): UserPayableParameter
    {
        $em = $this->userPayableParameterRepo->getEntityManager();
        $em->persist($userPayableParameter);
        $em->flush();

        return $userPayableParameter;
    }
}