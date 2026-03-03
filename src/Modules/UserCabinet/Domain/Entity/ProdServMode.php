<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Domain\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'prod_serv_modes')]
class ProdServMode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: Types::INTEGER)]
    private int $id;

    #[ORM\ManyToOne(targetEntity: ProductService::class)]
    #[ORM\JoinColumn(name: 'srv_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ProductService $service;

    #[ORM\Column(name: 'name', type: Types::STRING, length: 255)]
    private string $name;

    #[ORM\Column(name: 'is_visible', type: Types::INTEGER)]
    private int $isVisible = 1;

    #[ORM\Column(name: 'is_special', type: Types::INTEGER)]
    private int $isSpecial = 0;

    #[ORM\Column(name: 'is_singleton', type: Types::INTEGER)]
    private int $isSingleton = 0;

    #[ORM\Column(name: 'restrict_mask', type: Types::INTEGER)]
    private int $restrictMask = 1;

    #[ORM\Column(name: 'periods', type: Types::INTEGER)]
    private int $periods = 1;

    #[ORM\Column(name: 'has_free_period', type: Types::INTEGER)]
    private int $hasFreePeriod = 0;

    #[ORM\Column(name: 'priority', type: Types::INTEGER, nullable: true)]
    private ?int $priority = null;

    #[ORM\Column(name: 'psm_str_code', type: Types::STRING, length: 64, nullable: true)]
    private ?string $strCode = null;

    #[ORM\Column(name: 'description', type: Types::STRING, length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToOne(mappedBy: 'serviceMode', targetEntity: Tariff::class)]
    private ?Tariff $tariff = null;

    /** Стоимости режима (prod_serv_mode_costs) */
    #[ORM\OneToOne(mappedBy: 'mode', targetEntity: ProdServModeCost::class)]
    private ProdServModeCost $prodServModeCost;

    // --- getters / helpers ---
    public function getId(): int { return $this->id; }
    public function getService(): ProductService { return $this->service; }
    public function getName(): string { return $this->name; }
    public function getStrCode(): ?string { return $this->strCode; }
    public function isVisible(): bool { return (bool)$this->isVisible; }
    public function isSpecial(): bool { return (bool)$this->isSpecial; }
    public function isSingleton(): bool { return (bool)$this->isSingleton; }
    public function getPeriods(): int { return $this->periods; }

    public function getTariff(): ?Tariff
    {
        return $this->tariff;
    }
    /**
     * @return iterable|ProdServModeCost[]
     */
    public function getProdServModeCost(): ProdServModeCost
    {
        return $this->prodServModeCost;
    }
}
