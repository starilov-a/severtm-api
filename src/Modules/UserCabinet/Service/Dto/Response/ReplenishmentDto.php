<?php

namespace App\Modules\UserCabinet\Service\Dto\Response;

use App\Modules\UserCabinet\Entity\Replenishment;
use App\Modules\UserCabinet\Service\Dto\Dto;
use DateTimeInterface;

class ReplenishmentDto extends Dto
{
    private ?int $id;
    private ?string $login;
    private string $additionalInformation;
    private string $paymentType;
    private ?string $comment;
    private ?float $amount;
    private $date;


    /**
     * @param int|null $id
     * @param string|null $login
     * @param string $additionalInformation
     * @param string $paymentType
     * @param string|null $comment
     * @param float|null $amount
     * @param string $date
     */
    public function __construct($replenishment)
    {
        $this->id = $replenishment->getId();
        $this->login =  $replenishment->getLogin();
        $this->additionalInformation =  $replenishment->getWho();
        $this->paymentType =  $replenishment->getWho();
        $this->comment =  $replenishment->getComments();
        $this->amount =  $replenishment->getAmount();
        $this->date = date("Y-m-d H:i:s", $replenishment->getDateTs());
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function getAdditionalInformation(): string
    {
        return $this->additionalInformation;
    }

    public function getPaymentType(): string
    {
        return $this->paymentType;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function getDate(): string
    {
        return $this->date;
    }
}
