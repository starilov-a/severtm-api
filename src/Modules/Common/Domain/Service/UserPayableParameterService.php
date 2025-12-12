<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\Device;
use App\Modules\Common\Domain\Entity\UserPayable;
use App\Modules\Common\Domain\Entity\UserPayableParameter;
use App\Modules\Common\Domain\Repository\EnumParameterRepository;
use App\Modules\Common\Domain\Repository\UserPayableParameterRepository;

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

    }

    public function save(UserPayableParameter $userPayableParameter): UserPayableParameter
    {
        $em = $this->userPayableParameterRepo->getEntityManager();
        $em->persist($userPayableParameter);
        $em->flush();

        return $userPayableParameter;
    }
}