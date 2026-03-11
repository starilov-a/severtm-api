<?php

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'user_phones')]
class UserPhone
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'uid', referencedColumnName: 'id', nullable: false)]
    private User $user;

    #[ORM\Id]
    #[ORM\Column(name: 'phone_type_id', type: Types::STRING, length: 4)]
    private string $phoneTypeId;

    #[ORM\Column(name: 'phone', type: Types::BIGINT)]
    private string $phone;

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getPhoneTypeId(): string
    {
        return $this->phoneTypeId;
    }

    public function setPhoneTypeId(string $phoneTypeId): void
    {
        $this->phoneTypeId = $phoneTypeId;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }
}