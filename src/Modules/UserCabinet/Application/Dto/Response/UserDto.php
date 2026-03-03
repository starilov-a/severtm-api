<?php

namespace App\Modules\UserCabinet\Application\Dto\Response;

use App\Modules\UserCabinet\Domain\Entity\User;
use App\Modules\UserCabinet\Domain\Service\Dto\Dto;

class UserDto extends Dto
{
    private ?int $id = null;
    private string $login = '';
    private ?string $password = null;
    private string $fullName = '';
    private ?string $passport = null;
    private string $taxNumber;
    private ?\DateTimeInterface $birthdate = null;
    private string $email = '';
    private string $phoneExtra;
    private ?int $district = null;
    private ?int $flat = null;
    private int $isJuridical = 0;
    private float $bill = 0.0;
    private float $bonus = 0.0;
    private float $billAbs = 0.0;
    private bool $credit = false;
    private ?\DateTimeInterface $creditDeadline = null;
    private \DateTimeInterface $blockDate;
    private string $blockComments = '';
    private int $regDate = 0;
    private int $abPstart;
    private int $abPend;
    private int $abLdiscount;
    private int $privLevel = 0;
    private int $groupId = 0;
    private bool $deleted = false;
    private string $comments;

    /**
     * @param int|null $id
     * @param string $login
     * @param string|null $password
     * @param string $fullName
     * @param string|null $passport
     * @param string $taxNumber
     * @param \DateTimeInterface|null $birthdate
     * @param string $email
     * @param string $phoneExtra
     * @param AddressDto|null $address
     * @param int|null $district
     * @param int|null $flat
     * @param int $isJuridical
     * @param float $bill
     * @param float $bonus
     * @param float $billAbs
     * @param bool $credit
     * @param \DateTimeInterface|null $creditDeadline
     * @param \DateTimeInterface $blockDate
     * @param string $blockComments
     * @param int $regDate
     * @param int $abPstart
     * @param int $abPend
     * @param int $abLdiscount
     * @param int $privLevel
     * @param int $groupId
     * @param bool $deleted
     * @param string $comments
     * @param TariffDto|null $currentTariff
     * @param TariffDto|null $nextTariff
     * @param WebUserDto|null $webUser
     */
    public function __construct(User $user)
    {
        $this->id = $user->getId();
        $this->login = $user->getLogin();
        $this->password = $user->getPassword();
        $this->fullName = $user->getFullName();
//        $this->passport = $user->getPassport();
        $this->taxNumber = $user->getTaxNumber();
        $this->birthdate = $user->getBirthdate();
        $this->email = $user->getEmail();
        $this->phoneExtra = $user->getPhoneExtra();
        $this->district = $user->getDistrict();
        $this->flat = $user->getFlat();
        $this->isJuridical = $user->isJuridical();
        $this->bill = $user->getBill();
        $this->bonus = $user->getBonus();
        $this->billAbs = $user->getBillAbs();
        $this->credit = $user->isCredit();
        $this->blockDate = $user->getBlockDate();
        $this->blockComments = $user->getBlockComments();
        $this->regDate = $user->getRegDate();
        $this->abPstart = $user->getAbPstart();
        $this->abPend = $user->getAbPend();
        $this->abLdiscount = $user->getAbLdiscount();
        $this->privLevel = $user->getPrivLevel();
        $this->groupId = $user->getGroupId();
        $this->deleted = $user->isDeleted();
        $this->comments = $user->getComments();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    public function getPassport(): ?string
    {
        return $this->passport;
    }

    public function setPassport(?string $passport): void
    {
        $this->passport = $passport;
    }

    public function getTaxNumber(): string
    {
        return $this->taxNumber;
    }

    public function setTaxNumber(string $taxNumber): void
    {
        $this->taxNumber = $taxNumber;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(?\DateTimeInterface $birthdate): void
    {
        $this->birthdate = $birthdate;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPhoneExtra(): string
    {
        return $this->phoneExtra;
    }

    public function setPhoneExtra(string $phoneExtra): void
    {
        $this->phoneExtra = $phoneExtra;
    }

    public function getAddress(): ?AddressDto
    {
        return $this->address;
    }

    public function setAddress(?AddressDto $address): void
    {
        $this->address = $address;
    }

    public function getDistrict(): ?int
    {
        return $this->district;
    }

    public function setDistrict(?int $district): void
    {
        $this->district = $district;
    }

    public function getFlat(): ?int
    {
        return $this->flat;
    }

    public function setFlat(?int $flat): void
    {
        $this->flat = $flat;
    }

    public function isJuridical(): int
    {
        return $this->isJuridical;
    }

    public function setIsJuridical(int $isJuridical): void
    {
        $this->isJuridical = $isJuridical;
    }

    public function getBill(): float
    {
        return $this->bill;
    }

    public function setBill(float $bill): void
    {
        $this->bill = $bill;
    }

    public function getBonus(): float
    {
        return $this->bonus;
    }

    public function setBonus(float $bonus): void
    {
        $this->bonus = $bonus;
    }

    public function getBillAbs(): float
    {
        return $this->billAbs;
    }

    public function setBillAbs(float $billAbs): void
    {
        $this->billAbs = $billAbs;
    }

    public function isCredit(): bool
    {
        return $this->credit;
    }

    public function setCredit(bool $credit): void
    {
        $this->credit = $credit;
    }

    public function getCreditDeadline(): ?\DateTimeInterface
    {
        return $this->creditDeadline;
    }

    public function setCreditDeadline(?\DateTimeInterface $creditDeadline): void
    {
        $this->creditDeadline = $creditDeadline;
    }

    public function getBlockDate(): \DateTimeInterface
    {
        return $this->blockDate;
    }

    public function setBlockDate(\DateTimeInterface $blockDate): void
    {
        $this->blockDate = $blockDate;
    }

    public function getBlockComments(): string
    {
        return $this->blockComments;
    }

    public function setBlockComments(string $blockComments): void
    {
        $this->blockComments = $blockComments;
    }

    public function getRegDate(): int
    {
        return $this->regDate;
    }

    public function setRegDate(int $regDate): void
    {
        $this->regDate = $regDate;
    }

    public function getAbPstart(): int
    {
        return $this->abPstart;
    }

    public function setAbPstart(int $abPstart): void
    {
        $this->abPstart = $abPstart;
    }

    public function getAbPend(): int
    {
        return $this->abPend;
    }

    public function setAbPend(int $abPend): void
    {
        $this->abPend = $abPend;
    }

    public function getAbLdiscount(): int
    {
        return $this->abLdiscount;
    }

    public function setAbLdiscount(int $abLdiscount): void
    {
        $this->abLdiscount = $abLdiscount;
    }

    public function getPrivLevel(): int
    {
        return $this->privLevel;
    }

    public function setPrivLevel(int $privLevel): void
    {
        $this->privLevel = $privLevel;
    }

    public function getGroupId(): int
    {
        return $this->groupId;
    }

    public function setGroupId(int $groupId): void
    {
        $this->groupId = $groupId;
    }

    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): void
    {
        $this->deleted = $deleted;
    }

    public function getComments(): string
    {
        return $this->comments;
    }

    public function setComments(string $comments): void
    {
        $this->comments = $comments;
    }

    public function getCurrentTariff(): ?TariffDto
    {
        return $this->currentTariff;
    }

    public function setCurrentTariff(?TariffDto $currentTariff): void
    {
        $this->currentTariff = $currentTariff;
    }

    public function getNextTariff(): ?TariffDto
    {
        return $this->nextTariff;
    }

    public function setNextTariff(?TariffDto $nextTariff): void
    {
        $this->nextTariff = $nextTariff;
    }

    public function getWebUser(): ?WebUserDto
    {
        return $this->webUser;
    }

    public function setWebUser(?WebUserDto $webUser): void
    {
        $this->webUser = $webUser;
    } // DTO ��� ��������� �������� WebUser

}
