<?php declare(strict_types=1);

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'applications')]
#[ORM\Index(name: 'connect_search', columns: ['application_address', 'application_flat'])]
#[ORM\Index(name: 'daemon_connected', columns: ['application_district', 'application_connectdate'])]
#[ORM\Index(name: 'id', columns: ['application_id', 'application_district'])]
#[ORM\Index(name: 'fk_app_ref_app_st', columns: ['application_status'])]
#[ORM\Index(name: 'fk_app_ref_at', columns: ['application_type'])]
class Application
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'application_id', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $id;

    #[ORM\Column(name: 'application_flat', type: Types::STRING, length: 6)]
    private string $flat = '';

    /**
     * В SQL: SMALLINT UNSIGNED NOT NULL DEFAULT 0, без FK.
     * 0 обычно значит "не задано", поэтому не делаем relation.
     */
    #[ORM\Column(name: 'application_address', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $addressId = 0;

    #[ORM\Column(name: 'application_client', type: Types::STRING, length: 255)]
    private string $client = '';

    #[ORM\Column(name: 'application_phone', type: Types::STRING, length: 11)]
    private string $phone = '';

    #[ORM\Column(name: 'application_mobile', type: Types::STRING, length: 11)]
    private string $mobile = '';

    /**
     * В БД дефолт '0000-00-00 00:00:00' (MySQL legacy).
     * Doctrine просто читает как datetime.
     */
    #[ORM\Column(name: 'application_getdate', type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $getDate;

    #[ORM\Column(name: 'application_cost', type: Types::INTEGER, nullable: true, options: ['unsigned' => true])]
    private ?int $cost = null;

    #[ORM\Column(name: 'application_sum', type: Types::INTEGER, nullable: true, options: ['unsigned' => true])]
    private ?int $sum = null;

    #[ORM\Column(name: 'application_connectdate', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $connectDate = null;

    #[ORM\Column(name: 'application_cable', type: Types::INTEGER, nullable: true, options: ['unsigned' => true])]
    private ?int $cable = null;

    /**
     * enum('Да','Нет') -> в doctrine у тебя enum маппится как string (mapping_types).
     */
    #[ORM\Column(name: 'application_adapter', type: Types::STRING, length: 3, nullable: true)]
    private ?string $adapter = null;

    #[ORM\Column(name: 'application_clientid', type: Types::INTEGER, nullable: true, options: ['unsigned' => true])]
    private ?int $clientId = null;

    #[ORM\Column(name: 'application_builder', type: Types::STRING, length: 255)]
    private string $builder = '';

    /**
     * В SQL: int NOT NULL DEFAULT 0, без FK.
     */
    #[ORM\Column(name: 'application_district', type: Types::INTEGER)]
    private int $districtId = 0;

    #[ORM\Column(name: 'application_is_juridical', type: Types::INTEGER)]
    private int $isJuridical = 0;

    #[ORM\Column(name: 'z_tv', type: Types::INTEGER)]
    private int $zTv = 0;

    #[ORM\Column(name: 'application_lastsyncdate', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastSyncDate = null;

    #[ORM\ManyToOne(targetEntity: ApplicationStatus::class)]
    #[ORM\JoinColumn(
        name: 'application_status',
        referencedColumnName: 'status_value',
        nullable: false,
        onDelete: 'RESTRICT'
    )]
    private ApplicationStatus $status;

    #[ORM\ManyToOne(targetEntity: ApplicationType::class)]
    #[ORM\JoinColumn(name: 'application_type', referencedColumnName: 'at_id', nullable: true, onDelete: 'RESTRICT')]
    private ?ApplicationType $type = null;

    #[ORM\Column(name: 'application_ctv', type: Types::INTEGER)]
    private int $ctv = 0;

    // --- getters ---
    public function getId(): int { return $this->id; }

    public function getFlat(): string { return $this->flat; }
    public function getAddressId(): int { return $this->addressId; }

    public function getClient(): string { return $this->client; }
    public function getPhone(): string { return $this->phone; }
    public function getMobile(): string { return $this->mobile; }

    public function getGetDate(): \DateTimeInterface { return $this->getDate; }
    public function getConnectDate(): ?\DateTimeInterface { return $this->connectDate; }

    public function getCost(): ?int { return $this->cost; }
    public function getSum(): ?int { return $this->sum; }

    public function getCable(): ?int { return $this->cable; }
    public function getAdapter(): ?string { return $this->adapter; }

    public function getClientId(): ?int { return $this->clientId; }
    public function getBuilder(): string { return $this->builder; }

    public function getDistrictId(): int { return $this->districtId; }

    public function getStatus(): string { return $this->status; }

    public function getType(): ?ApplicationType { return $this->type; }

    public function isJuridical(): bool { return (bool)$this->isJuridical; }
    public function hasZTv(): bool { return (bool)$this->zTv; }
    public function hasCtv(): bool { return (bool)$this->ctv; }

    public function getLastSyncDate(): ?\DateTimeInterface { return $this->lastSyncDate; }
}