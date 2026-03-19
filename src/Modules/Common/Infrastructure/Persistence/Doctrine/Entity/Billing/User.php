<?php declare(strict_types=1);

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User
{

    #[ORM\OneToOne(targetEntity: WebUser::class, mappedBy: 'user')]
    private ?WebUser $webUser;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Discount::class)]
    private ?Discount $discount = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserTask::class)]
    private Collection $tasks;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ProdDiscountTemp::class)]
    private Collection $prodDiscountTemps;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this->prodDiscountTemps = new ArrayCollection();
    }

    /* ---------- PK ---------- */

    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private int $id;

    /* ---------- Аутентификация ---------- */

    #[ORM\Column(type: Types::STRING, length: 20, options: ['default' => ''])]
    private string $login = '';

    #[ORM\Column(type: Types::STRING, length: 16, nullable: true)]
    private ?string $password = null;

    /* ---------- Персональные данные ---------- */

    #[ORM\Column(name: 'full_name', type: Types::STRING, length: 60, options: ['default' => ''])]
    private string $fullName = '';

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $passport = null;

    #[ORM\Column(name: 'tax_number', type: Types::STRING, length: 35)]
    private string $taxNumber;

    #[ORM\Column(name: 'birthdate', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $birthdate = null;

    /* ---------- Контакты ---------- */

    #[ORM\Column(type: Types::STRING, length: 255, options: ['default' => ''])]
    private string $email = '';

    #[ORM\Column(name: 'phone_extra', type: Types::STRING, length: 35)]
    private string $phoneExtra;

    /* ---------- Адрес ---------- */

    #[ORM\ManyToOne(targetEntity: Address::class)]
    #[ORM\JoinColumn(name: 'user_address', referencedColumnName: 'address_id', nullable: true)]
    private ?Address $address = null;


    #[ORM\Column(name: 'user_district', type: Types::INTEGER, nullable: true)]
    private ?int $district = null;


    #[ORM\Column(name: 'user_flat', type: Types::SMALLINT, nullable: true, options: ['unsigned' => true])]
    private ?int $flat = null;

    /* ---------- Финансы ---------- */

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
    private int $isJuridical = 0;

    #[ORM\ManyToOne(targetEntity: UserJurState::class)]
    #[ORM\JoinColumn(name: 'is_juridical', referencedColumnName: 'entity_id', nullable: true)]
    private ?UserJurState $jurState = null;

    #[ORM\Column(type: Types::FLOAT, precision: 16, scale: 4, options: ['default' => 0])]
    private float $bill = 0.0;

    #[ORM\Column(type: Types::FLOAT, precision: 16, scale: 4, options: ['default' => 0])]
    private float $bonus = 0.0;

    #[ORM\Column(name: 'bill_abs', type: Types::FLOAT, precision: 16, scale: 4, options: ['default' => 0])]
    private float $billAbs = 0.0;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => 0])]
    private bool $credit = false;

    #[ORM\Column(name: 'credit_deadline', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $creditDeadline = null;


    /* ---------- Блокировка ---------- */

    #[ORM\ManyToOne(targetEntity: BlockState::class)]
    #[ORM\JoinColumn(name: 'block', referencedColumnName: 'block_id', nullable: false)]
    private BlockState $blockState;

    #[ORM\Column(name: 'block_date', type: Types::DATETIME_MUTABLE, options: ['default' => '1970-01-01 00:00:00'])]
    private \DateTimeInterface $blockDate;

    #[ORM\Column(name: 'block_comments', type: Types::STRING, length: 100, options: ['default' => ''])]
    private string $blockComments = '';

    /* ---------- Дата регистрации и абонплата ---------- */

    #[ORM\Column(name: 'reg_date', type: Types::INTEGER, options: ['unsigned' => true, 'default' => 0])]
    private int $regDate = 0;

    #[ORM\Column(name: 'ab_pstart', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $abPstart;

    #[ORM\Column(name: 'ab_pend', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $abPend;

    #[ORM\Column(name: 'ab_ldiscount', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $abLdiscount;

    /* ---------- Разное ---------- */

    #[ORM\Column(name: 'priv_level', type: Types::INTEGER, options: ['unsigned' => true, 'default' => 0])]
    private int $privLevel = 0;

    #[ORM\Column(name: 'group_id', type: Types::SMALLINT, options: ['unsigned' => true, 'default' => 0])]
    private int $groupId = 0;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => 0])]
    private bool $deleted = false;

    #[ORM\Column(name: 'comments', type: Types::TEXT)]
    private string $comments;

    #[ORM\ManyToOne(targetEntity: Tariff::class, fetch: 'EAGER')]
    #[ORM\JoinColumn(name: 'tariff', referencedColumnName: 'id', nullable: true)]
    private ?Tariff $currentTariff = null;

    #[ORM\ManyToOne(targetEntity: Tariff::class, fetch: 'EAGER')]
    #[ORM\JoinColumn(name: 'tariff_next', referencedColumnName: 'id', nullable: true)]
    private ?Tariff $nextTariff = null;

    #[ORM\Column(name: 'bw', type: Types::BIGINT, nullable: false, options: ['default' => 0])]
    private string $bw;

    #[ORM\Column(name: 'current_bw', type: Types::BIGINT, nullable: false, options: ['default' => 0])]
    private string $currentBw;
    /* ---------- Отношения с другими справочниками ---------- */

    #[ORM\ManyToOne(targetEntity: CustomerInn::class)]
    #[ORM\JoinColumn(name: 'customer_id', referencedColumnName: 'customer_id', nullable: true)]
    private ?CustomerInn $customerInn = null;
//
//    #[ORM\ManyToOne(targetEntity: Unit::class)]
//    #[ORM\JoinColumn(name: 'unit_id', referencedColumnName: 'id', nullable: false)]
//    private Unit $unit;

    // … остальные числовые/текстовые поля (tariff, bw, current_bw, traffic_counted и т. д.)
    // по той же схеме: #[ORM\Column(...)] или #[ORM\ManyToOne(...)]
    // -------------------------------------------------------------

    /* ---------- геттеры/сеттеры (сокращены для примера) ---------- */

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;
        return $this;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function setFullName(string $name): self
    {
        $this->fullName = $name;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }


    public function getAddress(): Address
    {
        return $this->address;
    }

    public function getRegion(): ?Region
    {
        return $this->getAddress()->getDistrict()->getRegion();
    }

    public function getDistrict(): ?int
    {
        return $this->district;
    }

    public function setDistrict(int $district): void
    {
        $this->district = $district;
    }

    public function getCurrentTariff(): ?Tariff
    {
        return $this->currentTariff;
    }

    public function getNextTariff(): ?Tariff
    {
        return $this->nextTariff;
    }

    public function getWebUser(): ?WebUser
    {
        return $this->webUser;
    }


    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function setPassport(?string $passport): self
    {
        $this->passport = $passport;
        return $this;
    }

    public function setTaxNumber(string $taxNumber): self
    {
        $this->taxNumber = $taxNumber;
        return $this;
    }

    public function setBirthdate(?\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;
        return $this;
    }

    public function setPhoneExtra(string $phoneExtra): self
    {
        $this->phoneExtra = $phoneExtra;
        return $this;
    }

    public function setFlat(?int $flat): self
    {
        $this->flat = $flat;
        return $this;
    }

    public function setIsJuridical(int $isJuridical): self
    {
        $this->isJuridical = $isJuridical;
        return $this;
    }

    public function setBill(float $bill): self
    {
        $this->bill = $bill;
        return $this;
    }

    public function setBonus(float $bonus): self
    {
        $this->bonus = $bonus;
        return $this;
    }

    public function setBillAbs(float $billAbs): self
    {
        $this->billAbs = $billAbs;
        return $this;
    }

    public function setRegDate(\DateTimeImmutable $regDate): self
    {
        $this->regDate = $regDate->getTimestamp();
        return $this;
    }

    public function setAbLdiscount(int $abLdiscount): self
    {
        $this->abLdiscount = $abLdiscount;
        return $this;
    }

    public function setPrivLevel(int $privLevel): self
    {
        $this->privLevel = $privLevel;
        return $this;
    }

    public function setGroupId(int $groupId): self
    {
        $this->groupId = $groupId;
        return $this;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;
        return $this;
    }

    public function setComments(string $comments): self
    {
        $this->comments = $comments;
        return $this;
    }

    public function getPassport(): ?string
    {
        return $this->passport;
    }

    public function getTaxNumber(): string
    {
        return $this->taxNumber;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function getPhoneExtra(): string
    {
        return $this->phoneExtra;
    }

    public function getFlat(): ?int
    {
        return $this->flat;
    }

    public function isJuridical(): int
    {
        return $this->isJuridical;
    }

    public function getBill(): float
    {
        return $this->bill;
    }

    public function getBonus(): float
    {
        return $this->bonus;
    }

    public function getBillAbs(): float
    {
        return $this->billAbs;
    }

    public function getBlockState(): BlockState
    {
        return $this->blockState;
    }

    public function setBlockState(BlockState $blockState): void
    {
        $this->blockState = $blockState;
    }

    public function isCredit(): bool
    {
        return $this->credit;
    }

    public function setCredit(bool $credit): void
    {
        $this->credit = $credit;
    }

    public function getCreditDeadline(): ?\DateTimeInterface
    {
        return $this->creditDeadline;
    }

    public function setCreditDeadline(\DateTimeInterface $creditDeadline): void
    {
        $this->creditDeadline = $creditDeadline;
    }

    public function getBlockDate(): \DateTimeInterface
    {
        return $this->blockDate;
    }

    public function setBlockDate(\DateTimeInterface $blockDate): void
    {
        $this->blockDate = $blockDate;
    }

    public function getBlockComments(): string
    {
        return $this->blockComments;
    }

    public function getRegDate(): \DateTimeImmutable
    {
        $date = new \DateTimeImmutable();
        return $date->setTimestamp($this->regDate);
    }

    public function getAbPstart(): int
    {
        return $this->abPstart;
    }

    public function setAbPstart(\DateTimeImmutable $abPstart): self
    {
        $this->abPstart = $abPstart->getTimestamp();
        return $this;
    }

    public function getAbPend(): int
    {
        return $this->abPend;
    }

    public function setAbPend(\DateTimeImmutable $abPend): self
    {
        $this->abPend = $abPend->getTimestamp();
        return $this;
    }

    public function getAbLdiscount(): int
    {
        return $this->abLdiscount;
    }

    public function getPrivLevel(): int
    {
        return $this->privLevel;
    }

    public function getGroupId(): int
    {
        return $this->groupId;
    }

    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    public function getComments(): string
    {
        return $this->comments;
    }

    public function setAddress(?Address $address): void
    {
        $this->address = $address;
    }

    public function setCurrentTariff(?Tariff $currentTariff): void
    {
        $this->currentTariff = $currentTariff;
    }

    public function setNextTariff(?Tariff $nextTariff): void
    {
        $this->nextTariff = $nextTariff;
    }

    public function getJurState(): ?UserJurState
    {
        return $this->jurState;
    }

    public function setJurState(?UserJurState $jurState): void
    {
        $this->jurState = $jurState;
    }

    public function getDiscount(): ?Discount
    {
        return $this->discount;
    }

    public function setDiscount(?Discount $discount): void
    {
        $this->discount = $discount;
        if ($discount !== null && $discount->getUser() !== $this) {
            $discount->setUser($this);
        }
    }

    /**
     * @return string
     */
    public function getBw(): string
    {
        return $this->bw;
    }

    /**
     * @return string
     */
    public function getCurrentBw(): string
    {
        return $this->currentBw;
    }

    public function setBw(string $bw): self
    {
        $this->bw = $bw;
        return $this;
    }

    public function setCurrentBw(string $currentBw): self
    {
        $this->currentBw = $currentBw;
        return $this;
    }

    /**
     * @return Collection<int, UserTask>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }
//
//    public function addTask(UserTask $task): void
//    {
//        if ($this->tasks->contains($task)) {
//            return;
//        }
//
//        $this->tasks->add($task);
//        $task->setUser($this);
//    }
//
//    public function removeTask(UserTask $task): void
//    {
//        $this->tasks->removeElement($task);
//    }

    /**
     * @return Collection<int, UserTask>
     */
    public function getTasksByStateAndType(UserTaskState $state, UserTaskType $type): Collection
    {
        return $this->tasks->filter(
            static fn(UserTask $task) => $task->getState() === $state && $task->getType() === $type
        );
    }

    /**
     * @return Collection<int, ProdDiscountTemp>
     */
    public function getProdDiscountTemps(): Collection
    {
        return $this->prodDiscountTemps;
    }

    public function getCustomerInn(): ?CustomerInn
    {
        return $this->customerInn;
    }

    public function setCustomerInn(?CustomerInn $customerInn): void
    {
        $this->customerInn = $customerInn;
    }
}
