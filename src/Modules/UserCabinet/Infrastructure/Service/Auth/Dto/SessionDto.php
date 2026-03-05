<?php
namespace App\Modules\UserCabinet\Infrastructure\Service\Auth\Dto;


use App\Modules\Common\Application\Dto\Dto;

class SessionDto extends Dto
{
    private bool $loggedIn;
    private ?int $userId;
    private ?string $userName;
    private array $perms = [];
    private array $permsBuilder = [];
    private ?int $district;
    private ?array $roles = [];

    function __construct(bool $loggedIn, int $userId, ?string $userName, array $perms, array $permsBuilder, ?int $district, ?array $roles)
    {
        $this->loggedIn = $loggedIn;
        $this->userId = $userId;
        $this->userName = $userName;
        $this->permsBuilder = $permsBuilder;
        $this->perms = $perms;
        $this->roles = $roles;
        $this->district = $district;
    }

    public function isLoggedIn(): bool
    {
        return $this->loggedIn;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getUserIp(): ?string
    {
        return $this->userIp;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function getPerms(): array
    {
        return $this->perms;
    }

    public function getPermsBuilder(): array
    {
        return $this->permsBuilder;
    }

    public function getDistrict(): ?int
    {
        return $this->district;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

}
