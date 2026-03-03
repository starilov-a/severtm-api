<?php

namespace App\Modules\UserCabinet\Domain\Entity;

use App\Modules\UserCabinet\Domain\Repository\DebtRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DebtRepository::class, readOnly: true)]
#[ORM\Table(name: 'prod_discount_temp')]
class Debt
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private int $id;

    /** users.id */
    #[ORM\Column(name: 'uid', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $userId;

    /** products_services.id (как код услуги) */
    #[ORM\Column(name: 'prod_code', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $productCode;

    /** prod_serv_mode_costs.id */
    #[ORM\Column(name: 'srvmodecost_id', type: Types::INTEGER)]
    private int $serviceModeCostId;

    /** Сумма долга (FLOAT(16,4)) */
    #[ORM\Column(name: 'qnt', type: Types::FLOAT)]
    private float $amount;

    /** Кол-во (минуты/ГБ и т.п.), FLOAT(16,4) */
    #[ORM\Column(name: 'number', type: Types::FLOAT)]
    private float $units;

    /** UNIX-time «даты начисления» долга */
    #[ORM\Column(name: 'discount_date', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $discountDateTs;

    #[ORM\Column(name: 'master', type: Types::STRING, length: 32, nullable: true)]
    private ?string $master = null;

    #[ORM\Column(name: 'prod_comments', type: Types::STRING, length: 255)]
    private string $comment = '';

    #[ORM\Column(name: 'bill_before', type: Types::FLOAT, nullable: true)]
    private ?float $balanceBefore = null;

    #[ORM\Column(name: 'bill_after', type: Types::FLOAT, nullable: true)]
    private ?float $balanceAfter = null;

    /** tinyint(1) — статус начисления (семантика проектная) */
    #[ORM\Column(name: 'prod_status', type: Types::SMALLINT, nullable: true)]
    private ?int $status = null;

    /** user_payables.upid */
    #[ORM\Column(name: 'upid', type: Types::INTEGER, nullable: true)]
    private ?int $payableId = null;

    /* -------- Getters -------- */

    public function getId(): int { return $this->id; }
    public function getUserId(): int { return $this->userId; }
    public function getProductCode(): int { return $this->productCode; }
    public function getServiceModeCostId(): int { return $this->serviceModeCostId; }

    public function getAmount(): float { return $this->amount; }
    public function getUnits(): float { return $this->units; }

    public function getDiscountDateTs(): int { return $this->discountDateTs; }
    public function getDiscountedAt(): \DateTimeImmutable
    {
        return (new \DateTimeImmutable())->setTimestamp($this->discountDateTs);
    }

    public function getMaster(): ?string { return $this->master; }
    public function getComment(): string { return $this->comment; }

    public function getBalanceBefore(): ?float { return $this->balanceBefore; }
    public function getBalanceAfter(): ?float { return $this->balanceAfter; }

    public function getStatus(): ?int { return $this->status; }
    public function getPayableId(): ?int { return $this->payableId; }
}