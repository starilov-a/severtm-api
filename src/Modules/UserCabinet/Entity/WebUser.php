<?php

namespace App\Modules\UserCabinet\Entity;

use App\Modules\UserCabinet\Repository\WebUserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WebUserRepository::class)]
#[ORM\Table(name: "web_users")]
class WebUser
{

    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'webUser')]
    #[ORM\JoinColumn(name: "uid", referencedColumnName: "id", nullable: false)]
    private User $user;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $uid;

    #[ORM\Column(type: "integer")]
    private int $enabled;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $creation_time;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $last_atime;

    #[ORM\Column(type: "string", length: 16)]
    private string $last_aip;

    #[ORM\Column(type: "string", length: 16)]
    private string $login;


    #[ORM\Column(type: "string", length: 32)]
    private string $passwd_hash;

    #[ORM\Column(type: "string", length: 64)]
    private string $name;

    #[ORM\Column(type: "string", length: 64)]
    private string $patronymic;

    #[ORM\Column(type: "string", length: 64)]
    private string $surname;

    #[ORM\Column(type: "string", length: 255)]
    private string $email;

    #[ORM\Column(type: "string", length: 32, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(type: "string", length: 16, options: ['default' => 'crypt'])]
    private string $hash_type = 'crypt';

    #[ORM\Column(type: "string", length: 32, nullable: true)]
    private ?string $passwd_hash_encrypt = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $activation_code = null;

    #[ORM\Column(type: "integer", options: ['default' => 1])]
    private int $status = 1;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $date_activation = null;

    #[ORM\Column(type: "integer", options: ['default' => 0])]
    private int $is_send_activation = 0;

    public function getUid(): int
    {
        return $this->uid;
    }

    public function setUid(int $uid): void
    {
        $this->uid = $uid;
    }

    public function getEnabled(): int
    {
        return $this->enabled;
    }

    public function setEnabled(int $enabled): void
    {
        $this->enabled = $enabled;
    }

    public function getCreationTime(): \DateTimeInterface
    {
        return $this->creation_time;
    }

    public function setCreationTime(\DateTimeInterface $creation_time): void
    {
        $this->creation_time = $creation_time;
    }

    public function getLastAtime(): \DateTimeInterface
    {
        return $this->last_atime;
    }

    public function setLastAtime(\DateTimeInterface $last_atime): void
    {
        $this->last_atime = $last_atime;
    }

    public function getLastAip(): string
    {
        return $this->last_aip;
    }

    public function setLastAip(string $last_aip): void
    {
        $this->last_aip = $last_aip;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function getPasswdHash(): string
    {
        return $this->passwd_hash;
    }

    public function setPasswdHash(string $passwd_hash): void
    {
        $this->passwd_hash = $passwd_hash;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPatronymic(): string
    {
        return $this->patronymic;
    }

    public function setPatronymic(string $patronymic): void
    {
        $this->patronymic = $patronymic;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
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

    public function getHashType(): string
    {
        return $this->hash_type;
    }

    public function setHashType(string $hash_type): void
    {
        $this->hash_type = $hash_type;
    }

    public function getPasswdHashEncrypt(): ?string
    {
        return $this->passwd_hash_encrypt;
    }

    public function setPasswdHashEncrypt(?string $passwd_hash_encrypt): void
    {
        $this->passwd_hash_encrypt = $passwd_hash_encrypt;
    }

    public function getActivationCode(): ?string
    {
        return $this->activation_code;
    }

    public function setActivationCode(?string $activation_code): void
    {
        $this->activation_code = $activation_code;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getDateActivation(): ?\DateTimeInterface
    {
        return $this->date_activation;
    }

    public function setDateActivation(?\DateTimeInterface $date_activation): void
    {
        $this->date_activation = $date_activation;
    }

    public function getIsSendActivation(): int
    {
        return $this->is_send_activation;
    }

    public function setIsSendActivation(int $is_send_activation): void
    {
        $this->is_send_activation = $is_send_activation;
    }

    public function getUser(): User
    {
        return $this->user;
    }


}
