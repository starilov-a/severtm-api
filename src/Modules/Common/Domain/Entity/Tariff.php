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

    #[ORM\OneToOne(targetEntity: ProdServMode::class, inversedBy: 'tariff')]
    #[ORM\JoinColumn(name: 'srvmode_id', referencedColumnName: 'id', nullable: false)]
    private ProdServMode $serviceMode;

    #[ORM\Column(name: 'maxbw', type: Types::BIGINT, options: ['unsigned' => true])]
    private string $maxBandwidth;

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
    public function getServiceModeId(): int { return $this->serviceMode->getId(); }
    public function getProdServMode(): ProdServMode{ return $this->serviceMode;}

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



    private function hasGroupCode(string $code): bool
    {
        // шаблон, который накладывается в указанную модель
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('code', $code))
            ->setMaxResults(1);

        // Doctrine построит SQL с WHERE code = ? AND tc_id = ? LIMIT 1
        return !$this->groups->matching($criteria)->isEmpty();
    }
}
