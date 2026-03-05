<?php

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'user_payables')]
class UserPayable
{
    #[ORM\Id]
    #[ORM\Column(name: 'upid', type: Types::INTEGER)]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    protected int $id;

    /** Финансовый период (fin_periods.id) */
    #[ORM\ManyToOne(targetEntity: FinPeriod::class)]
    #[ORM\JoinColumn(name: 'fid', referencedColumnName: 'id', nullable: false)]
    protected FinPeriod $finPeriod;

    /** Клиент (users.id) */
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'uid', referencedColumnName: 'id', nullable: false)]
    protected User $user;

    /** Режим услуги (prod_serv_modes.id) */
    #[ORM\ManyToOne(targetEntity: ProdServMode::class)]
    #[ORM\JoinColumn(name: 'srvmode_id', referencedColumnName: 'id', nullable: false)]
    protected ProdServMode $serviceMode;

    #[ORM\Column(name: 'srvcost_id', type: Types::INTEGER)]
    protected int $srvcostId = 1;

    /**
     * Финансовый период, за который делается перерасчёт (fid_refund).
     * В БД это INT с default 0 без FK, поэтому оставляем scalar.
     */
    #[ORM\Column(name: 'fid_refund', type: Types::INTEGER, options: ['default' => 0])]
    protected int $fidRefund = 0;

    /** user_payables_types.type_id */
    #[ORM\ManyToOne(targetEntity: UserPayableType::class)]
    #[ORM\JoinColumn(name: 'type', referencedColumnName: 'type_id', nullable: false)]
    protected UserPayableType $type;

    #[ORM\Column(name: 'cost', type: Types::DECIMAL, precision: 16, scale: 2, options: ['default' => '0.00'])]
    protected string $cost = '0.00';

    #[ORM\Column(name: 'units', type: Types::SMALLINT, options: ['default' => 1])]
    protected int $units = 1;

    #[ORM\Column(name: 'amount', type: Types::DECIMAL, precision: 16, scale: 2, options: ['default' => '0.00'])]
    protected string $amount = '0.00';

    #[ORM\Column(name: 'discount', type: Types::DECIMAL, precision: 16, scale: 2, options: ['default' => '0.00'])]
    protected string $discount = '0.00';

    #[ORM\Column(name: 'payable', type: Types::DECIMAL, precision: 16, scale: 2, options: ['default' => '0.00'])]
    protected string $payable = '0.00';

    #[ORM\Column(name: 'influence', type: Types::FLOAT, options: ['default' => 1])]
    protected float $influence = 1.0;

    #[ORM\Column(name: 'is_real', type: Types::SMALLINT, options: ['default' => 1])]
    protected int $isReal = 1;

    #[ORM\Column(name: 'is_applied', type: Types::SMALLINT, options: ['default' => 0])]
    protected int $isApplied = 0;

    #[ORM\Column(name: 'created_at', type: Types::DATETIME_IMMUTABLE)]
    protected \DateTimeImmutable $createdAt;

    /** Связанный user_serv_modes.usmid (если есть) */
    #[ORM\OneToOne(targetEntity: UserServMode::class)]
    #[ORM\JoinColumn(name: 'usmid', referencedColumnName: 'usmid', nullable: true)]
    protected ?UserServMode $userServMode = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getFinPeriod(): FinPeriod
    {
        return $this->finPeriod;
    }

    public function setFinPeriod(FinPeriod $finPeriod): void
    {
        $this->finPeriod = $finPeriod;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getServiceMode(): ProdServMode
    {
        return $this->serviceMode;
    }

    public function setServiceMode(ProdServMode $serviceMode): void
    {
        $this->serviceMode = $serviceMode;
    }

    public function getSrvcostId(): int
    {
        return $this->srvcostId;
    }

    public function setSrvcostId(int $srvcostId): void
    {
        $this->srvcostId = $srvcostId;
    }

    public function getFidRefund(): int
    {
        return $this->fidRefund;
    }

    public function setFidRefund(int $fidRefund): void
    {
        $this->fidRefund = $fidRefund;
    }

    public function getType(): UserPayableType
    {
        return $this->type;
    }

    public function setType(UserPayableType $type): void
    {
        $this->type = $type;
    }

    public function getCost(): string
    {
        return $this->cost;
    }

    public function setCost(string $cost): void
    {
        $this->cost = $cost;
    }

    public function getUnits(): int
    {
        return $this->units;
    }

    public function setUnits(int $units): void
    {
        $this->units = $units;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): void
    {
        $this->amount = $amount;
    }

    public function getDiscount(): string
    {
        return $this->discount;
    }

    public function setDiscount(string $discount): void
    {
        $this->discount = $discount;
    }

    public function getPayable(): string
    {
        return $this->payable;
    }

    public function setPayable(string $payable): void
    {
        $this->payable = $payable;
    }

    public function getInfluence(): float
    {
        return $this->influence;
    }

    public function setInfluence(float $influence): void
    {
        $this->influence = $influence;
    }

    public function getIsReal(): int
    {
        return $this->isReal;
    }

    public function setIsReal(int $isReal): void
    {
        $this->isReal = $isReal;
    }

    public function getIsApplied(): int
    {
        return $this->isApplied;
    }

    public function setIsApplied(int $isApplied): void
    {
        $this->isApplied = $isApplied;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUserServMode(): ?UserServMode
    {
        return $this->userServMode;
    }

    public function setUserServMode(?UserServMode $userServMode): void
    {
        $this->userServMode = $userServMode;
    }
}
