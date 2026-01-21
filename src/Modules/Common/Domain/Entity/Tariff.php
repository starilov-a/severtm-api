<?php

namespace App\Modules\Common\Domain\Entity;

use App\Modules\Common\Domain\Repository\TariffRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

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
    private string $bw;

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

    #[ORM\OneToOne(targetEntity: ProdServMode::class, inversedBy: 'tariff')]
    #[ORM\JoinColumn(name: 'srvmode_id', referencedColumnName: 'id', nullable: false)]
    private ProdServMode $serviceMode;

    #[ORM\Column(name: 'maxbw', type: Types::BIGINT, options: ['unsigned' => true])]
    private string $maxBw;

    /**
     * EXTRA_LAZY: contains(), count() и т.п. будут выполнять точечные запросы,
     * без загрузки всей коллекции.
     */
    #[ORM\ManyToMany(targetEntity: TariffGroup::class, fetch: 'EXTRA_LAZY')]
    #[ORM\JoinTable(name: 'tariffs_belong_groups')]
    #[ORM\JoinColumn(name: 'tc_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    #[ORM\InverseJoinColumn(name: 'tariffs_group_id', referencedColumnName: 'tariffs_group_id', nullable: false, onDelete: 'CASCADE')]
    private Collection $groups;

    // --- getters (сократил до основных; добавь по необходимости) ---
    public function getId(): int { return $this->id; }
    public function getTid(): int { return $this->tid; }
    public function getName(): ?string { return $this->name; }
    public function getPrice(): float { return (float) $this->price; }
    public function getTariffPeriod(): int { return $this->tariffPeriod; }
    public function getServiceModeId(): int { return $this->serviceMode->getId(); }
    public function getProdServMode(): ProdServMode{ return $this->serviceMode;}
    public function getBw(): string {return $this->bw;}
    public function getMaxBw(): string {return $this->maxBw;}

    // TODO: сделать через группу "Отключен от сети"(?)
    public function isDisconnected(): bool
    {
        return $this->id === 1;
    }

    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }

    public function isInGroup(TariffGroup $group): bool
    {
        return $this->groups->contains($group); // EXTRA_LAZY → SELECT 1 … LIMIT 1
    }

    public function canBeChangedByClient(): bool // использую для теста - 12922
    {
        return $this->hasGroupCode('canBeChangeByClient');
    }

    public function hasGroupCode(string $code): bool
    {
        // шаблон, который накладывается в указанную модель
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('code', $code))
            ->setMaxResults(1);

        // Doctrine построит SQL с WHERE code = ? AND tc_id = ? LIMIT 1
        return !$this->groups->matching($criteria)->isEmpty();
    }

    public function setDepType(int $depType): void
    {
        $this->depType = $depType;
    }

    public function setDiscount(float $discount): void
    {
        $this->discount = $discount;
    }

    public function setTarNext(?int $tarNext): void
    {
        $this->tarNext = $tarNext;
    }

    public function setTarUser(string $tarUser): void
    {
        $this->tarUser = $tarUser;
    }

    public function setTarMaster(string $tarMaster): void
    {
        $this->tarMaster = $tarMaster;
    }

    public function setTarAdmin(string $tarAdmin): void
    {
        $this->tarAdmin = $tarAdmin;
    }

    public function setEndAt(?\DateTimeInterface $endAt): void
    {
        $this->endAt = $endAt;
    }

    public function setStartAt(?\DateTimeInterface $startAt): void
    {
        $this->startAt = $startAt;
    }

    public function setTariffPeriod(?int $tariffPeriod): void
    {
        $this->tariffPeriod = $tariffPeriod;
    }

    public function setBw(string $bw): void
    {
        $this->bw = $bw;
    }

    public function setMaxBw(string $maxBw): void
    {
        $this->maxBw = $maxBw;
    }

    public function setExcessCost(string $excessCost): void
    {
        $this->excessCost = $excessCost;
    }

    public function setPrepaidTraffic(string $prepaidTraffic): void
    {
        $this->prepaidTraffic = $prepaidTraffic;
    }

    public function setPrice(string $price): void
    {
        $this->price = $price;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function setChangeUserId(int $changeUserId): void
    {
        $this->changeUserId = $changeUserId;
    }

    public function setChangeDate(int $changeDate): void
    {
        $this->changeDate = $changeDate;
    }

    public function setTid(int $tid): void
    {
        $this->tid = $tid;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
