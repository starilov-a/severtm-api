<?php

namespace App\Modules\Common\Domain\Entity;

use App\Modules\Common\Domain\Repository\UserOwnDeviceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserOwnDeviceRepository::class)]
#[ORM\Table(name: 'user_own_devices')]
class UserOwnDevice
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'uid', referencedColumnName: 'id', nullable: false)]
    protected User $user;

    #[ORM\Id]
    #[ORM\OneToOne(targetEntity: Device::class)]
    #[ORM\JoinColumn(name: 'device_id', referencedColumnName: 'device_id', nullable: false)]
    protected Device $device;

    #[ORM\Column(name: 'time_stamp', type: Types::DATETIME_MUTABLE)]
    protected \DateTimeInterface $timeStamp;

    #[ORM\Column(name: 'master_uid', type: Types::INTEGER, nullable: true, options: ['unsigned' => true])]
    protected ?int $masterUid = null;

    #[ORM\Column(name: 'device_comment', type: Types::STRING, length: 255, nullable: true)]
    protected ?string $deviceComment = null;

    public function getId(): int
    {
        // В БД PK составной (uid + device_id), тут можно возвращать device_id как идентификатор для удобства,
        // либо вообще не использовать этот метод вне специфичных кейсов.
        return $this->device->getId();
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function setDevice(Device $device): void
    {
        $this->device = $device;
    }

    public function setTimeStamp(\DateTimeInterface $timeStamp): void
    {
        $this->timeStamp = $timeStamp;
    }

    public function setMasterUid(?int $masterUid): void
    {
        $this->masterUid = $masterUid;
    }

    public function setDeviceComment(?string $deviceComment): void
    {
        $this->deviceComment = $deviceComment;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getDevice(): Device
    {
        return $this->device;
    }

    public function getTimeStamp(): \DateTimeInterface
    {
        return $this->timeStamp;
    }

    public function getMasterUid(): ?int
    {
        return $this->masterUid;
    }

    public function getDeviceComment(): ?string
    {
        return $this->deviceComment;
    }


}
