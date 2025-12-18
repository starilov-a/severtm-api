<?php

namespace App\Modules\Common\Domain\Entity;

use App\Modules\Common\Domain\Repository\ReplenishmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReplenishmentRepository::class, readOnly: true)]
#[ORM\Table(name: 'bills_history')]
class Replenishment
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: Types::STRING, length: 20)]
    private string $login;

    /** UNIX-время регистрации пополнения (секунды) */
    #[ORM\Column(name: '`date`', type: Types::INTEGER)]
    private int $dateTs;

    /** Сумма пополнения (FLOAT(16,4) в БД) */
    #[ORM\Column(name: 'qnt', type: Types::FLOAT)]
    private float $amount;

    /** Кто провёл (ПС/оператор) */
    #[ORM\Column(name: 'who', type: Types::STRING, length: 40)]
    private string $who;

    /** Код/тип операции (например, платёжная система) */
    #[ORM\Column(name: 'what', type: Types::STRING, length: 26)]
    private string $what;

    #[ORM\Column(name: 'comments', type: Types::TEXT)]
    private string $comments;

    #[ORM\Column(name: 'currency_id', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $currencyId;

    /** Сумма в валюте платежа */
    #[ORM\Column(name: 'qnt_currency', type: Types::FLOAT)]
    private float $amountCurrency;

    /** Фактическая дата оплаты (UNIX seconds) */
    #[ORM\Column(name: 'real_pay_date', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $realPayDateTs;

    /** users.id */
    private int $userId;
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'uid', referencedColumnName: 'id', nullable: false)]
    protected User $user;

    /** Перенесён ли  */
    #[ORM\Column(name: 'has_been_transferred', type: Types::INTEGER)]
    private int $hasBeenTransferred;

    /** Автоматизированный ли платёж (0/1) */
    #[ORM\Column(name: 'is_automated', type: Types::INTEGER)]
    private int $isAutomated;

    #[ORM\Column(name: 'refund_comment', type: Types::TEXT, nullable: true)]
    private ?string $refundComment = null;

    /* -------- Getters -------- */

    public function getId(): int { return $this->id; }
    public function getLogin(): string { return $this->login; }
    public function getUserId(): int { return $this->userId; }

    public function getAmount(): float { return $this->amount; }
    public function getAmountCurrency(): float { return $this->amountCurrency; }

    public function getCurrencyId(): int { return $this->currencyId; }
    public function getWho(): string { return $this->who; }
    public function getWhat(): string { return $this->what; }

    public function getComments(): string { return $this->comments; }
    public function getRefundComment(): ?string { return $this->refundComment; }

    public function getDateTs(): int { return $this->dateTs; }
    public function getRealPayDateTs(): int { return $this->realPayDateTs; }

    public function getBookedAt(): \DateTimeImmutable
    {
        return (new \DateTimeImmutable())->setTimestamp($this->dateTs);
    }

    public function getPaidAt(): \DateTimeImmutable
    {
        return (new \DateTimeImmutable())->setTimestamp($this->realPayDateTs);
    }

    public function isTransferred(): bool { return (bool) $this->hasBeenTransferred; }
    public function isAutomated(): bool { return (bool) $this->isAutomated; }
}