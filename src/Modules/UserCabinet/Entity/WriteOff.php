<?php

namespace App\Modules\UserCabinet\Entity;

use App\Modules\UserCabinet\Repository\WriteOffRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WriteOffRepository::class)]
#[ORM\Table(name: 'prod_discount_history')]
class WriteOff
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private int $id;

    /** users.id */
    #[ORM\Column(name: 'uid', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $userId;

    /** Опциональный код продукта (как в БД) */
    #[ORM\Column(name: 'prod_code', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $productCode;

    /** prod_serv_mode_costs.id */
    #[ORM\Column(name: 'srvmodecost_id', type: Types::INTEGER)]
    private int $serviceModeCostId;

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
    #[ORM\Column(name: 'master', type: Types::STRING, length: 20)]
    private string $master;

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

    /** user_payables.upid (если есть связь) */
    #[ORM\Column(name: 'upid', type: Types::INTEGER, nullable: true)]
    private ?int $payableId = null;

    /* ---------------- Getters ---------------- */

    public function getId(): int { return $this->id; }
    public function getUserId(): int { return $this->userId; }
    public function getProductCode(): int { return $this->productCode; }
    public function getServiceModeCostId(): int { return $this->serviceModeCostId; }
    public function getAmount(): float { return $this->amount; }
    public function getUnits(): float { return $this->units; }
    public function getDiscountDateTs(): int { return $this->discountDateTs; }
    public function getMaster(): string { return $this->master; }
    public function getComment(): ?string { return $this->comment; }
    public function getBalanceBefore(): ?float { return $this->balanceBefore; }
    public function getBalanceAfter(): ?float { return $this->balanceAfter; }
    public function getChargedAt(): \DateTimeImmutable { return $this->chargedAt; }
    public function getPayableId(): ?int { return $this->payableId; }

    /** Удобный доступ к discount_date как к дате */
    public function getDiscountedAt(): ?\DateTimeImmutable
    {
        return $this->discountDateTs > 0
            ? (new \DateTimeImmutable())->setTimestamp($this->discountDateTs)
            : null;
    }
}