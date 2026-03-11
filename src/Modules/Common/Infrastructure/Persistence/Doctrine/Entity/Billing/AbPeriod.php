<?php

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'ab_periods')]
class AbPeriod
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'uid', referencedColumnName: 'id', nullable: false)]
    private User $user;

    #[ORM\Column(name: 'ab_pstart', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $startedAtTs;

    #[ORM\Column(name: 'ab_pend', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $endedAtTs;

    #[ORM\Column(name: 'balance_start', type: Types::FLOAT)]
    private float $balanceStart = 0.0;

    #[ORM\Column(name: 'balance_end', type: Types::FLOAT)]
    private float $balanceEnd = 0.0;

    #[ORM\Column(name: 'tid_start', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $tidStart;

    #[ORM\Column(name: 'is_delete', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $isDelete = 0;

    public function setUser(User $user): void { $this->user = $user; }
    public function setStartedAtTs(int $startedAtTs): void { $this->startedAtTs = $startedAtTs; }
    public function setEndedAtTs(int $endedAtTs): void { $this->endedAtTs = $endedAtTs; }
    public function setBalanceStart(float $balanceStart): void { $this->balanceStart = $balanceStart; }
    public function setBalanceEnd(float $balanceEnd): void { $this->balanceEnd = $balanceEnd; }
    public function setTidStart(int $tidStart): void { $this->tidStart = $tidStart; }
}
