<?php

namespace App\Modules\Common\Domain\Entity;

use App\Modules\Common\Domain\Repository\ProdDiscountHistoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProdDiscountHistoryRepository::class)]
#[ORM\Table(name: 'prod_discount_history')]
class ProdDiscountHistory
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private int $id;

    /** users.id */
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'uid', referencedColumnName: 'id', nullable: false)]
    protected User $user;

    /** Опциональный код продукта (как в БД) */
    #[ORM\Column(name: 'prod_code', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $productCode;

    /** prod_serv_mode_costs.id */
    #[ORM\ManyToOne(targetEntity: ProdServModeCost::class)]
    #[ORM\JoinColumn(name: 'srvmodecost_id', referencedColumnName: 'id', nullable: false)]
    protected ProdServModeCost $modeCost;

    /** Сумма списания (FLOAT(16,4) в БД) */
    #[ORM\Column(name: 'qnt', type: Types::FLOAT)]
    private float $amount;

    /** Кол-во единиц (минуты/ГБ и т.п.), FLOAT(16,4) */
    #[ORM\Column(name: 'number', type: Types::FLOAT)]
    private float $units;

    /** UNIX-time списания (секунды) */
    #[ORM\Column(name: 'discount_date', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $discountDateTs;

    /** Доп. пометка (мастер/подуслуга) */
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'master', referencedColumnName: 'id', nullable: false)]
    private User $master;

    #[ORM\Column(name: 'prod_comments', type: Types::STRING, length: 128, nullable: true)]
    private ?string $comment = null;

    /** Баланс до операции */
    #[ORM\Column(name: 'bill_before', type: Types::FLOAT, nullable: true)]
    private ?float $balanceBefore = null;

    /** Баланс после операции */
    #[ORM\Column(name: 'bill_after', type: Types::FLOAT, nullable: true)]
    private ?float $balanceAfter = null;

    /** TIMESTAMP проведения */
    #[ORM\Column(name: 'charge_date', type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $chargedAt;

    #[ORM\OneToOne(targetEntity: UserPayable::class)]
    #[ORM\JoinColumn(name: 'upid', referencedColumnName: 'upid', nullable: true)]
    protected ?UserPayable $payable = null;

    /* ---------------- Getters ---------------- */

    public function getId(): int { return $this->id; }
    public function getUser(): User { return $this->user; }
    public function getProductCode(): int { return $this->productCode; }
    public function getServiceModeCost(): ProdServModeCost { return $this->modeCost; }
    public function getAmount(): float { return $this->amount; }
    public function getUnits(): float { return $this->units; }
    public function getDiscountDateTs(): int { return $this->discountDateTs; }
    public function getMaster(): User { return $this->master; }
    public function getComment(): ?string { return $this->comment; }
    public function getBalanceBefore(): ?float { return $this->balanceBefore; }
    public function getBalanceAfter(): ?float { return $this->balanceAfter; }
    public function getChargedAt(): \DateTimeImmutable { return $this->chargedAt; }
    public function getPayable(): ?UserPayable
    {
        return $this->payable;
    }

    /** Удобный доступ к discount_date как к дате */
    public function getDiscountedAt(): ?\DateTimeImmutable
    {
        return $this->discountDateTs > 0
            ? (new \DateTimeImmutable())->setTimestamp($this->discountDateTs)
            : null;
    }
}