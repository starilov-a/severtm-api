<?php

namespace App\Modules\UserCabinet\Domain\Service\Dto\Request;

use App\Modules\UserCabinet\Domain\Service\Dto\Dto;

class WebUserDto extends Dto
{
    private int $uid;
    private ?string $email;
    private ?string $phone;
    private ?string $comment;
    private ?string $passwd_hash;


    /**
     * @param $id
     * @param string|null $email
     * @param string|null $phone
     * @param string|null $comment
     * @param string|null $passwd_hash
     */
    public function __construct($id, ?string $email = null, ?string $phone = null, ?string $comment = null, ?string $passwd_hash = null)
    {
        $this->uid = $id;
        $this->email = $email;
        $this->phone = $phone;
        $this->comment = $comment;
        $this->passwd_hash = $passwd_hash;

    }


    public function getUid(): int
    {
        return $this->uid;
    }

    public function setUid(int $uid): void
    {
        $this->uid = $uid;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    public function getPasswdHash(): ?string
    {
        return $this->passwd_hash;
    }

    public function setPasswdHash(?string $passwd_hash): void
    {
        $this->passwd_hash = $passwd_hash;
    }
}
