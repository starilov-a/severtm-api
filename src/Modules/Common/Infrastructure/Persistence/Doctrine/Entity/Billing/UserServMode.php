<?php declare(strict_types=1);

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
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

    //не испольщзуется
    #[ORM\Column(name: 'srvcost_id', type: Types::INTEGER)]
    private int $servCostId = 1;

    // прочие ссылки оставим как scalar, чтобы не плодить entity
    #[ORM\Column(name: 'ext', type: Types::INTEGER, nullable: true)]
    private ?int $ext = null;

    #[ORM\Column(name: 'units', type: Types::INTEGER, options: ['default' => 1])]
    private int $units = 1;

    #[ORM\Column(name: 'is_active', type: Types::INTEGER, options: ['default' => 0])]
    private int $isActive = 0;

    #[ORM\Column(name: 'use_cost', type: Types::INTEGER, options: ['default' => 1])]
    private int $useCost = 1;

    #[ORM\ManyToOne(targetEntity: Device::class)]
    #[ORM\JoinColumn(name: 'device_id', referencedColumnName: 'device_id', nullable: true)]
    private ?Device $device = null;

    /**
     * Ассоциированная запись user_own_devices (не мэппится напрямую,
     * может устанавливаться доменным сервисом после выборки).
     */
    private ?UserOwnDevice $userOwnDevice = null;

    #[ORM\Column(name: 'edit_date', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $editedAt = null;

    // --- getters / helpers ---
    public function getId(): int { return $this->id; }
    public function getFinPeriod(): FinPeriod { return $this->finPeriod; }
    public function getUser(): User { return $this->user; }
    public function getMode(): ProdServMode { return $this->mode; }
    public function getServCostId(): int { return $this->servCostId; }
    public function isActive(): bool { return (bool)$this->isActive; }
    public function usesCost(): bool { return (bool)$this->useCost; }

    public function getDevice(): ?Device
    {
        return $this->device;
    }

    public function setDevice(?Device $device): self
    {
        $this->device = $device;
        return $this;
    }

    public function getUserOwnDevice(): ?UserOwnDevice
    {
        return $this->userOwnDevice;
    }

    public function setUserOwnDevice(?UserOwnDevice $userOwnDevice): self
    {
        $this->userOwnDevice = $userOwnDevice;
        return $this;
    }

    public function setEditedAt(?\DateTimeInterface $editedAt): void
    {
        $this->editedAt = $editedAt;
    }

    public function setUseCost(int $useCost): void
    {
        $this->useCost = $useCost;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = (int) $isActive;
    }

    public function setUnits(int $units): void
    {
        $this->units = $units;
    }

    public function getUnits(): int
    {
        return $this->units;
    }

    public function setExt(?int $ext): void
    {
        $this->ext = $ext;
    }

    public function setServCost(int $servCostId): void
    {
        $this->servCostId = $servCostId;
    }
    public function setMode(ProdServMode $mode): void
    {
        $this->mode = $mode;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function setFinPeriod(FinPeriod $finPeriod): void
    {
        $this->finPeriod = $finPeriod;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
