<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use App\Modules\UserCabinet\Repository\UserServModeRepository;

#[ORM\Entity(repositoryClass: UserServModeRepository::class)]
#[ORM\Table(name: 'user_serv_modes')]
#[ORM\Index(name: 'idx_usm_fid_uid', columns: ['fid', 'uid'])]
class UserServMode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'usmid', type: Types::INTEGER)]
    private int $id;

    // FK → fin_periods.id (у вас уже есть сущность FinPeriod)
    #[ORM\ManyToOne(targetEntity: FinPeriod::class)]
    #[ORM\JoinColumn(name: 'fid', referencedColumnName: 'id', nullable: false, onDelete: 'RESTRICT')]
    private FinPeriod $finPeriod;

    // FK → users.id (сущность User есть у вас)
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'uid', referencedColumnName: 'id', nullable: false, onDelete: 'RESTRICT')]
    private User $user;

    // FK → prod_serv_modes.id
    #[ORM\ManyToOne(targetEntity: ProdServMode::class)]
    #[ORM\JoinColumn(name: 'srvmode_id', referencedColumnName: 'id', nullable: false, onDelete: 'RESTRICT')]
    private ProdServMode $mode;

    // внешняя сущность стоимости — у вас её нет, оставим как scalar
    #[ORM\Column(name: 'srvcost_id', type: Types::INTEGER)]
    private int $serviceCostId;

    // прочие ссылки оставим как scalar, чтобы не плодить entity
    #[ORM\Column(name: 'ext', type: Types::INTEGER, nullable: true)]
    private ?int $ext = null;

    #[ORM\Column(name: 'units', type: Types::INTEGER, options: ['default' => 1])]
    private int $units = 1;

    #[ORM\Column(name: 'is_active', type: Types::INTEGER, options: ['default' => 0])]
    private int $isActive = 0;

    #[ORM\Column(name: 'use_cost', type: Types::INTEGER, options: ['default' => 1])]
    private int $useCost = 1;

    #[ORM\Column(name: 'device_id', type: Types::INTEGER, nullable: true)]
    private ?int $deviceId = null;

    #[ORM\Column(name: 'edit_date', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $editedAt = null;

    // --- getters / helpers ---
    public function getId(): int { return $this->id; }
    public function getFinPeriod(): FinPeriod { return $this->finPeriod; }
    public function getUser(): User { return $this->user; }
    public function getMode(): ProdServMode { return $this->mode; }
    public function getServiceCostId(): int { return $this->serviceCostId; }
    public function isActive(): bool { return (bool)$this->isActive; }
    public function usesCost(): bool { return (bool)$this->useCost; }
}
