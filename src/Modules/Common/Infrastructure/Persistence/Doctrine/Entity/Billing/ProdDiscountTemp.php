<?php

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'prod_discount_temp')]
class ProdDiscountTemp
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: Types::INTEGER, options: ['unsigned' => true])]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    protected int $id;

    /** Клиент, для которого создаётся запись */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'prodDiscountTemps')]
    #[ORM\JoinColumn(name: 'uid', referencedColumnName: 'id', nullable: false)]
    protected User $user;

    /** Продукт/услуга (prod_code = ProductService.id) */
    #[ORM\ManyToOne(targetEntity: ProductService::class)]
    #[ORM\JoinColumn(name: 'prod_code', referencedColumnName: 'id', nullable: false)]
    protected ProductService $product;

    /** Стоимость режима (prod_serv_mode_costs.id) */
    #[ORM\ManyToOne(targetEntity: ProdServModeCost::class)]
    #[ORM\JoinColumn(name: 'srvmodecost_id', referencedColumnName: 'id', nullable: false)]
    protected ProdServModeCost $modeCost;

    #[ORM\Column(name: 'qnt', type: Types::FLOAT, precision: 16, scale: 4, options: ['default' => 0])]
    protected float $qnt = 0.0;

    #[ORM\Column(name: 'number', type: Types::FLOAT, precision: 16, scale: 4, options: ['default' => 0])]
    protected float $number = 0.0;

    /** UNIX‑timestamp */
    #[ORM\Column(name: 'discount_date', type: Types::INTEGER, options: ['unsigned' => true, 'default' => 0])]
    protected int $discountDate = 0;

    /**
     * Логин пользователя, инициировавшего операцию (users.login).
     * В БД хранится как строка, поэтому оставляем scalar, а не связь.
     */
    #[ORM\Column(name: 'master', type: Types::STRING, length: 32, nullable: true)]
    protected ?string $master = null;

    #[ORM\Column(name: 'prod_comments', type: Types::STRING, length: 255, options: ['default' => ''])]
    protected string $prodComments = '';

    #[ORM\Column(name: 'bill_before', type: Types::FLOAT, precision: 16, scale: 4, nullable: true)]
    protected ?float $billBefore = null;

    #[ORM\Column(name: 'bill_after', type: Types::FLOAT, precision: 16, scale: 4, nullable: true)]
    protected ?float $billAfter = null;

    #[ORM\Column(name: 'prod_status', type: Types::BOOLEAN, nullable: true)]
    protected ?bool $prodStatus = false;

    /** Связанный платёж/списание (user_payables.upid) */
    #[ORM\ManyToOne(targetEntity: UserPayable::class)]
    #[ORM\JoinColumn(name: 'upid', referencedColumnName: 'upid', nullable: true)]
    protected ?UserPayable $payable = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getProduct(): ProductService
    {
        return $this->product;
    }

    public function setProduct(ProductService $product): self
    {
        $this->product = $product;
        return $this;
    }

    public function getModeCost(): ProdServModeCost
    {
        return $this->modeCost;
    }

    public function setModeCost(ProdServModeCost $modeCost): self
    {
        $this->modeCost = $modeCost;
        return $this;
    }

    public function getQnt(): float
    {
        return $this->qnt;
    }

    public function setQnt(float $qnt): self
    {
        $this->qnt = $qnt;
        return $this;
    }

    public function getNumber(): float
    {
        return $this->number;
    }

    public function setNumber(float $number): self
    {
        $this->number = $number;
        return $this;
    }

    public function getDiscountDate(): int
    {
        return $this->discountDate;
    }

    public function setDiscountDate(int $discountDate): self
    {
        $this->discountDate = $discountDate;
        return $this;
    }

    public function getMaster(): ?string
    {
        return $this->master;
    }

    public function setMaster(?string $master): self
    {
        $this->master = $master;
        return $this;
    }

    public function getProdComments(): string
    {
        return $this->prodComments;
    }

    public function setProdComments(string $prodComments): self
    {
        $this->prodComments = $prodComments;
        return $this;
    }

    public function getBillBefore(): ?float
    {
        return $this->billBefore;
    }

    public function setBillBefore(?float $billBefore): self
    {
        $this->billBefore = $billBefore;
        return $this;
    }

    public function getBillAfter(): ?float
    {
        return $this->billAfter;
    }

    public function setBillAfter(?float $billAfter): self
    {
        $this->billAfter = $billAfter;
        return $this;
    }

    public function getProdStatus(): ?bool
    {
        return $this->prodStatus;
    }

    public function setProdStatus(?bool $prodStatus): self
    {
        $this->prodStatus = $prodStatus;
        return $this;
    }

    public function getPayable(): ?UserPayable
    {
        return $this->payable;
    }

    public function setPayable(?UserPayable $payable): self
    {
        $this->payable = $payable;
        return $this;
    }
}
