<?php

namespace App\Modules\UserCabinet\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Modules\UserCabinet\Repository\TariffRepository;

#[ORM\Entity(repositoryClass: TariffRepository::class, readOnly: true)]
#[ORM\Table(name: 'tariffs_current')]
class Tariff
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private int $id;

    #[ORM\Column(name: 'tid', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $tid;

    #[ORM\Column(name: 'change_date', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $changeDate;

    #[ORM\Column(name: 'change_uid', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $changeUserId;

    #[ORM\Column(name: 'name', type: Types::TEXT, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(name: 'price', type: Types::DECIMAL, precision: 16, scale: 2)]
    private string $price; // decimal как string

    #[ORM\Column(name: 'prepaid_traf', type: Types::BIGINT, options: ['unsigned' => true])]
    private string $prepaidTraffic;

    #[ORM\Column(name: 'excess_cost', type: Types::DECIMAL, precision: 16, scale: 2)]
    private string $excessCost;

    #[ORM\Column(name: 'bw', type: Types::BIGINT, options: ['unsigned' => true])]
    private string $bandwidth;

    #[ORM\Column(name: 'tar_period', type: Types::INTEGER, nullable: true, options: ['unsigned' => true])]
    private ?int $tariffPeriod = null;

    #[ORM\Column(name: 'tar_startdate', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $startAt = null;

    #[ORM\Column(name: 'tar_enddate', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endAt = null;

    #[ORM\Column(name: 'tar_admin', type: Types::STRING, length: 1)]
    private string $tarAdmin;

    #[ORM\Column(name: 'tar_master', type: Types::STRING, length: 1)]
    private string $tarMaster;

    #[ORM\Column(name: 'tar_user', type: Types::STRING, length: 1)]
    private string $tarUser;

    #[ORM\Column(name: 'tar_next', type: Types::INTEGER, nullable: true, options: ['unsigned' => true])]
    private ?int $tarNext = null;

    #[ORM\Column(name: 'discount', type: Types::FLOAT)]
    private float $discount;

    #[ORM\Column(name: 'deptype', type: Types::INTEGER)]
    private int $depType;

    #[ORM\Column(name: 'srvmode_id', type: Types::INTEGER)]
    private int $serviceModeId;

    #[ORM\Column(name: 'maxbw', type: Types::BIGINT, options: ['unsigned' => true])]
    private string $maxBandwidth;

    // --- getters (сократил до основных; добавь по необходимости) ---
    public function getId(): int { return $this->id; }
    public function getTid(): int { return $this->tid; }
    public function getName(): ?string { return $this->name; }
    public function getPrice(): string { return $this->price; }
    public function getServiceModeId(): int { return $this->serviceModeId; }
}
