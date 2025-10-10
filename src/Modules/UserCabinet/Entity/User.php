<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Entity;

use App\Modules\UserCabinet\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
class User
{

    #[ORM\OneToOne(targetEntity: WebUser::class, mappedBy: 'user')]
    private ?WebUser $webUser;

    /* ---------- PK ---------- */

    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private int $id;

    /* ---------- Аутентификация ---------- */

    #[ORM\Column(type: Types::STRING, length: 20, options: ['default' => ''])]
    private string $login = '';

    #[ORM\Column(type: Types::STRING, length: 16, nullable: true)]
    private ?string $password = null;

    /* ---------- Персональные данные ---------- */

    #[ORM\Column(name: 'full_name', type: Types::STRING, length: 60, options: ['default' => ''])]
    private string $fullName = '';

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $passport = null;

    #[ORM\Column(name: 'tax_number', type: Types::STRING, length: 35)]
    private string $taxNumber;

    #[ORM\Column(name: 'birthdate', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $birthdate = null;

    /* ---------- Контакты ---------- */

    #[ORM\Column(type: Types::STRING, length: 255, options: ['default' => ''])]
    private string $email = '';

    #[ORM\Column(name: 'phone_extra', type: Types::STRING, length: 35)]
    private string $phoneExtra;

    /* ---------- Адрес ---------- */

    #[ORM\ManyToOne(targetEntity: Address::class)]
    #[ORM\JoinColumn(name: 'user_address', referencedColumnName: 'address_id', nullable: true)]
    private ?Address $address = null;

    #[ORM\Column(name: 'user_district', type: Types::INTEGER, nullable: true)]
    private ?int $district = null;


    #[ORM\Column(name: 'user_flat', type: Types::SMALLINT, nullable: true, options: ['unsigned' => true])]
    private ?int $flat = null;

    /* ---------- Финансы ---------- */

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => 0])]
    private bool $isJuridical = false;

    #[ORM\Column(type: Types::FLOAT, precision: 16, scale: 4, options: ['default' => 0])]
    private float $bill = 0.0;

    #[ORM\Column(type: Types::FLOAT, precision: 16, scale: 4, options: ['default' => 0])]
    private float $bonus = 0.0;

    #[ORM\Column(name: 'bill_abs', type: Types::FLOAT, precision: 16, scale: 4, options: ['default' => 0])]
    private float $billAbs = 0.0;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => 0])]
    private bool $credit = false;

    #[ORM\Column(name: 'credit_deadline', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $creditDeadline = null;


    /* ---------- Блокировка ---------- */

//    #[ORM\ManyToOne(targetEntity: BlockState::class)]
//    #[ORM\JoinColumn(name: 'block', referencedColumnName: 'block_id', nullable: false)]
//    private BlockState $blockState;

    #[ORM\Column(name: 'block_date', type: Types::DATETIME_MUTABLE, options: ['default' => '1970-01-01 00:00:00'])]
    private \DateTimeInterface $blockDate;

    #[ORM\Column(name: 'block_comments', type: Types::STRING, length: 100, options: ['default' => ''])]
    private string $blockComments = '';

    /* ---------- Дата регистрации и абонплата ---------- */

    #[ORM\Column(name: 'reg_date', type: Types::INTEGER, options: ['unsigned' => true, 'default' => 0])]
    private int $regDate = 0;

    #[ORM\Column(name: 'ab_pstart', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $abPstart;

    #[ORM\Column(name: 'ab_pend', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $abPend;

    #[ORM\Column(name: 'ab_ldiscount', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $abLdiscount;

    /* ---------- Разное ---------- */

    #[ORM\Column(name: 'priv_level', type: Types::INTEGER, options: ['unsigned' => true, 'default' => 0])]
    private int $privLevel = 0;

    #[ORM\Column(name: 'group_id', type: Types::SMALLINT, options: ['unsigned' => true, 'default' => 0])]
    private int $groupId = 0;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => 0])]
    private bool $deleted = false;

    #[ORM\Column(name: 'comments', type: Types::TEXT)]
    private string $comments;

    #[ORM\ManyToOne(targetEntity: Tariff::class, fetch: 'EAGER')]
    #[ORM\JoinColumn(name: 'tariff', referencedColumnName: 'id', nullable: true)]
    private ?Tariff $currentTariff = null;

    #[ORM\ManyToOne(targetEntity: Tariff::class, fetch: 'EAGER')]
    #[ORM\JoinColumn(name: 'tariff_next', referencedColumnName: 'id', nullable: true)]
    private ?Tariff $nextTariff = null;

    /* ---------- Отношения с другими справочниками ---------- */

//    #[ORM\ManyToOne(targetEntity: Customer::class)]
//    #[ORM\JoinColumn(name: 'customer_id', referencedColumnName: 'customer_id', nullable: true)]
//    private ?Customer $customer = null;
//
//    #[ORM\ManyToOne(targetEntity: Unit::class)]
//    #[ORM\JoinColumn(name: 'unit_id', referencedColumnName: 'id', nullable: false)]
//    private Unit $unit;

    // … остальные числовые/текстовые поля (tariff, bw, current_bw, traffic_counted и т. д.)
    // по той же схеме: #[ORM\Column(...)] или #[ORM\ManyToOne(...)]
    // -------------------------------------------------------------

    /* ---------- геттеры/сеттеры (сокращены для примера) ---------- */

    public function getId(): int
    {
        return $this->id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;
        return $this;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function setFullName(string $name): self
    {
        $this->fullName = $name;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }


    public function getAddress(): Address
    {
        return $this->address;
    }

    public function getDistrict(): ?int
    {
        return $this->district;
    }

    public function getCurrentTariff(): ?Tariff { return $this->currentTariff; }

    public function getNextTariff(): ?Tariff { return $this->nextTariff; }
}
