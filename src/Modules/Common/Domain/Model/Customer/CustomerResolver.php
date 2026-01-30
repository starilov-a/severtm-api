<?php

namespace App\Modules\Common\Domain\Model\Customer;


use App\Modules\Common\Domain\Entity\User;

class CustomerResolver
{
    public function fromUser(User $user): CustomerModel
    {
        if ($user->isJuridical() && false) {
            $inn = $user->getCustomerInn()->getInn();

            $customer = new B2BCustomerModel();
            $customer->setKey(CustomerKey::legalInn($inn));
            $customer->setInn($inn);
            $customer->setLogin($user->getLogin());

            return $customer;
        }

        // Физик: клиент = договор
        $customer = new B2CCustomerModel();
        $customer->setKey(CustomerKey::personContractId($user->getId()));
        $customer->setLogin($user->getLogin());

        return $customer;
    }
}