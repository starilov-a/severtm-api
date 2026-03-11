<?php

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'z_ipaddr')]
class ZIpaddr
{
    #[ORM\Id]
    #[ORM\Column(name: 'ip', type: Types::STRING, length: 16)]
    private string $ip;

    #[ORM\Column(name: 'ip_bin', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $ipBin = 0;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'uid', referencedColumnName: 'id', nullable: false)]
    private User $user;

    #[ORM\Column(name: 'switch_ip', type: Types::STRING, length: 16)]
    private string $switchIp = '';

    #[ORM\Column(name: 'switch_port', type: Types::SMALLINT, options: ['unsigned' => true])]
    private int $switchPort = 0;

    #[ORM\Column(name: 'mac', type: Types::STRING, length: 20)]
    private string $mac = '';

    #[ORM\Column(name: 'district', type: Types::INTEGER, nullable: true, options: ['unsigned' => true])]
    private ?int $district = null;

    #[ORM\Column(name: 'fw_on', type: Types::BOOLEAN, options: ['default' => 0])]
    private bool $fwOn = false;

    #[ORM\Column(name: 'ip_ext', type: Types::STRING, length: 16)]
    private string $ipExt = '';

    #[ORM\Column(name: 'gw', type: Types::STRING, length: 16)]
    private string $gw = '';

    #[ORM\Column(name: 'bind', type: Types::BOOLEAN, options: ['default' => 1])]
    private bool $bind = true;

    public function getIp(): string { return $this->ip; }
    public function setIp(string $ip): void { $this->ip = $ip; }
    public function getIpBin(): int { return $this->ipBin; }
    public function setIpBin(int $ipBin): void { $this->ipBin = $ipBin; }
    public function getUser(): User { return $this->user; }
    public function setUser(User $user): void { $this->user = $user; }
    public function getSwitchIp(): string { return $this->switchIp; }
    public function setSwitchIp(string $switchIp): void { $this->switchIp = $switchIp; }
    public function getSwitchPort(): int { return $this->switchPort; }
    public function setSwitchPort(int $switchPort): void { $this->switchPort = $switchPort; }
    public function getMac(): string { return $this->mac; }
    public function setMac(string $mac): void { $this->mac = $mac; }
    public function getDistrict(): ?int { return $this->district; }
    public function setDistrict(?int $district): void { $this->district = $district; }
    public function isFwOn(): bool { return $this->fwOn; }
    public function setFwOn(bool $fwOn): void { $this->fwOn = $fwOn; }
    public function getIpExt(): string { return $this->ipExt; }
    public function setIpExt(string $ipExt): void { $this->ipExt = $ipExt; }
    public function getGw(): string { return $this->gw; }
    public function setGw(string $gw): void { $this->gw = $gw; }
    public function isBind(): bool { return $this->bind; }
    public function setBind(bool $bind): void { $this->bind = $bind; }
}