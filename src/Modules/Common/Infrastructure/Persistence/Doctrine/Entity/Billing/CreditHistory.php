<?php declare(strict_types=1);

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'credits_history', options: ['charset' => 'cp1251'])]
#[ORM\Index(name: 'master', columns: ['credit_master'])]
#[ORM\Index(name: 'date', columns: ['credit_date'])]
#[ORM\Index(name: 'uid', columns: ['credit_uid'])]
class CreditHistory
{
    /**
     * В таблице `credits_history` нет surrogate PK.
     * Используем составной ключ, достаточный для однозначной адресации записи.
     */
    #[ORM\Id]
    #[ORM\Column(name: 'credit_date', type: Types::DATETIME_MUTABLE)]
    private \DateTimeImmutable $creditDate;

    #[ORM\Id]
    #[ORM\Column(name: 'credit_deadline', type: Types::DATE_MUTABLE)]
    private \DateTimeImmutable $creditDeadline;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'credit_uid', referencedColumnName: 'id', nullable: false)]
    private User $user;

    #[ORM\Column(name: 'credit_sum', type: Types::FLOAT, precision: 7, scale: 2, options: ['unsigned' => true, 'default' => 0])]
    private float $creditSum = 0.0;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'credit_master', referencedColumnName: 'id', nullable: false)]
    private User $master;

    #[ORM\Column(name: 'credit_bill', type: Types::FLOAT, precision: 16, scale: 4, options: ['default' => 0])]
    private float $creditBill = 0.0;

    public function getCreditDate(): \DateTimeImmutable
    {
        return $this->creditDate;
    }

    public function setCreditDate(\DateTimeImmutable $creditDate): self
    {
        $this->creditDate = $creditDate;
        return $this;
    }

    public function getCreditDeadline(): \DateTimeImmutable
    {
        return $this->creditDeadline;
    }

    public function setCreditDeadline(\DateTimeImmutable $creditDeadline): self
    {
        $this->creditDeadline = $creditDeadline;
        return $this;
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

    public function getCreditSum(): float
    {
        return $this->creditSum;
    }

    public function setCreditSum(float $creditSum): self
    {
        $this->creditSum = $creditSum;
        return $this;
    }

    public function getMaster(): User
    {
        return $this->master;
    }

    public function setMaster(User $master): self
    {
        $this->master = $master;
        return $this;
    }

    public function getCreditBill(): float
    {
        return $this->creditBill;
    }

    public function setCreditBill(float $creditBill): self
    {
        $this->creditBill = $creditBill;
        return $this;
    }
}

