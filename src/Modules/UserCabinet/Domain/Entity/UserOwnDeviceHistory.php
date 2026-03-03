<?php

namespace App\Modules\UserCabinet\Domain\Entity;

use App\Modules\UserCabinet\Domain\Repository\UserOwnDeviceHistoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserOwnDeviceHistoryRepository::class)]
#[ORM\Table(name: 'user_own_devices_history')]
class UserOwnDeviceHistory
{
    #[ORM\Id]
    #[ORM\Column(name: 'time_stamp', type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $timeStamp;

    #[ORM\Column(name: 'tag', type: Types::STRING, length: 1, nullable: true)]
    private ?string $tag = null;

    /** Владелец устройства в момент записи (uid) */
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'uid', referencedColumnName: 'id', nullable: true)]
    private ?User $user = null;

    /** Устройство (device_id) */
    #[ORM\ManyToOne(targetEntity: Device::class)]
    #[ORM\JoinColumn(name: 'device_id', referencedColumnName: 'device_id', nullable: true)]
    private ?Device $device = null;

    /** Пользователь, выполнивший операцию (master_uid) */
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'master_uid', referencedColumnName: 'id', nullable: true)]
    private ?User $masterUser = null;

    #[ORM\Column(name: 'device_comment', type: Types::STRING, length: 255, nullable: true)]
    private ?string $deviceComment = null;

    /** Серийный номер / номер устройства (device_num) */
    #[ORM\Column(name: 'device_num', type: Types::STRING, length: 255)]
    private string $deviceNum;

    public function __construct()
    {
        $this->timeStamp = new \DateTimeImmutable();
    }

    public function getTimeStamp(): \DateTimeImmutable
    {
        return $this->timeStamp;
    }

    public function setTimeStamp(\DateTimeImmutable $timeStamp): self
    {
        $this->timeStamp = $timeStamp;
        return $this;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(?string $tag): self
    {
        $this->tag = $tag;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getDevice(): ?Device
    {
        return $this->device;
    }

    public function setDevice(?Device $device): self
    {
        $this->device = $device;
        return $this;
    }

    public function getMasterUser(): ?User
    {
        return $this->masterUser;
    }

    public function setMasterUser(?User $masterUser): self
    {
        $this->masterUser = $masterUser;
        return $this;
    }

    public function getDeviceComment(): ?string
    {
        return $this->deviceComment;
    }

    public function setDeviceComment(?string $deviceComment): self
    {
        $this->deviceComment = $deviceComment;
        return $this;
    }

    public function getDeviceNum(): string
    {
        return $this->deviceNum;
    }

    public function setDeviceNum(string $deviceNum): self
    {
        $this->deviceNum = $deviceNum;
        return $this;
    }
}

