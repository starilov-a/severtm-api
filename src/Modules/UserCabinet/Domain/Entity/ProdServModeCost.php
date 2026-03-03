<?php

namespace App\Modules\UserCabinet\Domain\Entity;

use App\Modules\UserCabinet\Domain\Repository\ProdServModeCostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProdServModeCostRepository::class)]
#[ORM\Table(name: 'prod_serv_mode_costs')]
#[ORM\UniqueConstraint(name: 'psmc_srvmode_id__srvcost_id', columns: ['srvmode_id', 'srvcost_id'])]
class ProdServModeCost
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: Types::INTEGER)]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\OneToOne(targetEntity: ProdServMode::class, inversedBy: 'prodServModeCost')]
    #[ORM\JoinColumn(name: 'srvmode_id', referencedColumnName: 'id', nullable: false)]
    private ProdServMode $mode;

    /** prod_cost_modes.id */
    #[ORM\Column(name: 'srvcost_id', type: Types::INTEGER)]
    private int $costModeId = 1;

    #[ORM\Column(name: 'cost', type: Types::DECIMAL, precision: 16, scale: 2)]
    private string $cost;

    #[ORM\Column(name: 'bonus_discount', type: Types::DECIMAL, precision: 10, scale: 2, options: ['default' => '0.00'])]
    private string $bonusDiscount = '0.00';

    #[ORM\Column(name: 'allow_refund', type: Types::INTEGER, options: ['default' => 0])]
    private int $allowRefund = 0;

    public function getId(): int
    {
        return $this->id;
    }

    public function getMode(): ProdServMode
    {
        return $this->mode;
    }

    public function setMode(ProdServMode $mode): self
    {
        $this->mode = $mode;
        return $this;
    }

    public function getCostModeId(): int
    {
        return $this->costModeId;
    }

    public function setCostModeId(int $costModeId): self
    {
        $this->costModeId = $costModeId;
        return $this;
    }

    public function getCost(): float
    {
        return (float) $this->cost;
    }

    public function setCost(float $cost): self
    {
        $this->cost = number_format($cost, 2, '.', '');
        return $this;
    }

    public function getBonusDiscount(): float
    {
        return (float) $this->bonusDiscount;
    }

    public function setBonusDiscount(float $bonusDiscount): self
    {
        $this->bonusDiscount = number_format($bonusDiscount, 2, '.', '');
        return $this;
    }

    public function isRefundAllowed(): bool
    {
        return (bool) $this->allowRefund;
    }

    public function setAllowRefund(bool $allow): self
    {
        $this->allowRefund = $allow ? 1 : 0;
        return $this;
    }
}
