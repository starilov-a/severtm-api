<?php

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'web_user_ip_addrs')]
class WebUserIpAddr
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: WebUser::class)]
    #[ORM\JoinColumn(name: 'uid', referencedColumnName: 'uid', nullable: false)]
    private WebUser $webUser;

    #[ORM\Id]
    #[ORM\Column(name: 'ip', type: Types::STRING, length: 16)]
    private string $ip;

    #[ORM\Column(name: 'persistent', type: Types::BOOLEAN, options: ['default' => 0])]
    private bool $persistent = false;

    public function getWebUser(): WebUser
    {
        return $this->webUser;
    }

    public function setWebUser(WebUser $webUser): void
    {
        $this->webUser = $webUser;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function setIp(string $ip): void
    {
        $this->ip = $ip;
    }

    public function isPersistent(): bool
    {
        return $this->persistent;
    }

    public function setPersistent(bool $persistent): void
    {
        $this->persistent = $persistent;
    }
}