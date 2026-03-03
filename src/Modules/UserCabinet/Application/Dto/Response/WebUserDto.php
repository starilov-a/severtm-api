<?php

namespace App\Modules\UserCabinet\Service\Dto\Response;

use App\Modules\Common\Domain\Entity\WebUser;
use App\Modules\Common\Domain\Service\Dto\Dto;

class WebUserDto extends Dto
{
    private int $uid;
    private int $enabled;
    private \DateTimeInterface $creationTime;
    private \DateTimeInterface $lastAtime;
    private string $lastAip;
    private string $login;
    private string $name;
    private string $patronymic;
    private string $surname;
    private string $email;
    private ?string $phone = null;
    private ?string $comment = null;
    private ?string $activationCode = null;
    private int $status = 1;
    private ?\DateTimeInterface $dateActivation = null;
    private int $isSendActivation = 0;

    /**
     * @param int $uid
     * @param int $enabled
     * @param \DateTimeInterface $creationTime
     * @param \DateTimeInterface $lastAtime
     * @param string $lastAip
     * @param string $login
     * @param string $name
     * @param string $patronymic
     * @param string $surname
     * @param string $email
     * @param string|null $phone
     * @param string|null $comment
     * @param string|null $activationCode
     * @param int $status
     * @param \DateTimeInterface|null $dateActivation
     * @param int $isSendActivation
     */
    public function __construct(WebUser $webUser)
    {
        $this->uid = $webUser->getUid();
        $this->enabled = $webUser->getEnabled();
        $this->creationTime = $webUser->getCreationTime();
        $this->lastAtime = $webUser->getLastAtime();
        $this->lastAip = $webUser->getLastAip();
        $this->login = $webUser->getLogin();
        $this->name = $webUser->getName();
        $this->patronymic = $webUser->getPatronymic();
        $this->surname = $webUser->getSurname();
        $this->email = $webUser->getEmail();
        $this->phone = $webUser->getPhone();
        $this->comment = $webUser->getComment();
        $this->activationCode = $webUser->getActivationCode();
        $this->status = $webUser->getStatus();
        $this->dateActivation = $webUser->getDateActivation();
        $this->isSendActivation = $webUser->getIsSendActivation();
    }


    public function getUid(): int
    {
        return $this->uid;
    }

    public function getEnabled(): int
    {
        return $this->enabled;
    }

    public function getCreationTime(): \DateTimeInterface
    {
        return $this->creationTime;
    }

    public function getLastAtime(): \DateTimeInterface
    {
        return $this->lastAtime;
    }

    public function getLastAip(): string
    {
        return $this->lastAip;
    }

    public function getLogin(): string
    {
        return $this->login;
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function getPatronymic(): string
    {
        return $this->patronymic;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getActivationCode(): ?string
    {
        return $this->activationCode;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getDateActivation(): ?\DateTimeInterface
    {
        return $this->dateActivation;
    }

    public function getIsSendActivation(): int
    {
        return $this->isSendActivation;
    }

}
