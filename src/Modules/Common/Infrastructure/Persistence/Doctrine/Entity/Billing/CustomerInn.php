<?php declare(strict_types=1);

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'customers')]
#[ORM\UniqueConstraint(name: 'customer_inn', columns: ['customer_inn'])]
class CustomerInn
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'customer_id', type: Types::INTEGER)]
    private ?int $id = null;

    // В легаси реально заполнено
    #[ORM\Column(name: 'customer_inn', type: Types::STRING, length: 16)]
    private string $inn;

    #[ORM\Column(
        name: 'time_stamp',
        type: Types::DATETIME_MUTABLE,
        options: ['default' => 'CURRENT_TIMESTAMP']
    )]
    private \DateTimeInterface $timeStamp;

    // Остальные поля сейчас пустые, но маппим для совместимости
    #[ORM\Column(name: 'customer_name', type: Types::STRING, length: 255, options: ['default' => ''])]
    private string $name = '';

    #[ORM\Column(name: 'customer_ownership', type: Types::STRING, length: 16, nullable: true)]
    private ?string $ownership = null;

    #[ORM\Column(name: 'ogrn', type: Types::STRING, length: 150, options: ['default' => ''])]
    private string $ogrn = '';

    #[ORM\Column(name: 'fullNameFromEgrul', type: Types::STRING, length: 255, options: ['default' => ''])]
    private string $fullNameFromEgrul = '';

    #[ORM\Column(name: 'shortNameFromEgrul', type: Types::STRING, length: 255, options: ['default' => ''])]
    private string $shortNameFromEgrul = '';

    public function __construct(string $inn)
    {
        $this->inn = $inn;
        $this->timeStamp = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInn(): string
    {
        return $this->inn;
    }

    public function setInn(string $inn): void
    {
        $this->inn = $inn;
    }

    public function getTimeStamp(): \DateTimeInterface
    {
        return $this->timeStamp;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOwnership(): ?string
    {
        return $this->ownership;
    }

    public function getOgrn(): string
    {
        return $this->ogrn;
    }

    public function getFullNameFromEgrul(): string
    {
        return $this->fullNameFromEgrul;
    }

    public function getShortNameFromEgrul(): string
    {
        return $this->shortNameFromEgrul;
    }
}
