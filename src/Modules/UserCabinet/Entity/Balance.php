<?php

namespace App\Modules\UserCabinet\Entity;

use App\Modules\UserCabinet\Repository\BalanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BalanceRepository::class, readOnly: true)]
#[ORM\Table(name: 'users')]
class Balance
{
    /** ID пользователя == PK в таблице users */
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $userId;

    #[ORM\Column(name: 'bill', type: Types::INTEGER, options: ['default' => 0])]
    private int $amount = 0;

    public function getUserId(): int
    {
        return $this->userId;
    }

    /** Текущий баланс */
    public function get(): int
    {
        return $this->amount;
    }
}