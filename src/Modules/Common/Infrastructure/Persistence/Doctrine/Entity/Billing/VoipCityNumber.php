<?php

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'voip_city_numbers')]
class VoipCityNumber
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'citynumber_id', type: Types::INTEGER)]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'uid', referencedColumnName: 'id', nullable: false)]
    private User $user;

    #[ORM\Column(name: 'n_status', type: Types::INTEGER)]
    private int $status;

    #[ORM\Column(name: 'time_status', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $timeStatus = null;

    public function setUser(User $user): void { $this->user = $user; }
    public function setStatus(int $status): void { $this->status = $status; }
    public function setTimeStatus(?\DateTimeInterface $timeStatus): void { $this->timeStatus = $timeStatus; }
}