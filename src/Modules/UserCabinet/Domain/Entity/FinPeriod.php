<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Domain\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'fin_periods')]
#[ORM\Index(name: 'idx_fin_periods_curr', columns: ['is_current'])]
#[ORM\Index(name: 'idx_fin_periods_stdate', columns: ['start_date'])]
#[ORM\Index(name: 'idx_fin_periods_eddate', columns: ['end_date'])]
class FinPeriod
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(name: 'start_date', type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $startDate;

    #[ORM\Column(name: 'end_date', type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $endDate;

    // MySQL TINYINT(4); храним как bool
    #[ORM\Column(name: 'is_current', type: Types::BOOLEAN)]
    private bool $isCurrent = false;

    public function __construct(\DateTimeImmutable $startDate, \DateTimeImmutable $endDate, bool $isCurrent = false)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
        $this->isCurrent = $isCurrent;
    }

    public function getId(): int { return $this->id; }

    public function getStartDate(): \DateTimeImmutable { return $this->startDate; }
    public function setStartDate(\DateTimeImmutable $date): self { $this->startDate = $date; return $this; }

    public function getEndDate(): \DateTimeImmutable { return $this->endDate; }
    public function setEndDate(\DateTimeImmutable $date): self { $this->endDate = $date; return $this; }

    public function isCurrent(): bool { return $this->isCurrent; }
    public function setCurrent(bool $current): self { $this->isCurrent = $current; return $this; }

    public function contains(\DateTimeInterface $when): bool
    {
        return $when >= $this->startDate && $when <= $this->endDate;
    }
}