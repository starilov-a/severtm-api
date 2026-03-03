<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Domain\Entity;

use App\Modules\UserCabinet\Domain\Repository\ProductServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductServiceRepository::class)]
#[ORM\Table(name: 'products_services')]
#[ORM\UniqueConstraint(name: 'uidx_prod_serv_code', columns: ['str_code'])]
class ProductService
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $id;

    #[ORM\Column(name: 'prod_name', type: Types::TEXT)]
    private string $name;

    #[ORM\Column(name: 'price', type: Types::FLOAT)]
    private float $price = 0.0;

    #[ORM\Column(name: 'is_periodic', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $isPeriodic = 0;

    #[ORM\Column(name: 'discount_at_begin', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $discountAtBegin = 0;

    #[ORM\Column(name: 'recalc_prod_infact', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $recalcProdInfact = 0;

    // enum('0','1') в БД — оставим как string, но дадим bool-геттер
    #[ORM\Column(name: 'selfservice', type: Types::STRING, length: 1)]
    private string $selfService = '0';

    #[ORM\Column(name: 'service_description', type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    // enum('N','Y') — тоже строка + bool-геттер
    #[ORM\Column(name: 'prod_admin', type: Types::STRING, length: 1, nullable: true)]
    private ?string $prodAdmin = 'Y';

    #[ORM\Column(name: 'priority', type: Types::INTEGER)]
    private int $priority = 0;

    // колонка называется `order`, зарезервированное слово — используем другое имя свойства
    #[ORM\Column(name: '`order`', type: Types::INTEGER, nullable: true)]
    private ?int $sortOrder = null;

    #[ORM\Column(name: 'str_code', type: Types::STRING, length: 32, nullable: true)]
    private ?string $strCode = null;

    #[ORM\Column(name: 'is_visible', type: Types::INTEGER)]
    private int $isVisible = 1;

    private ?array $modes =[];
    private ?array $userModes =[];
    #[ORM\ManyToMany(targetEntity: PsGroup::class, inversedBy: 'services')]
    #[ORM\JoinTable(name: 'ps_belong_groups')]
    #[ORM\JoinColumn(name: 'ps_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'ps_group_id', referencedColumnName: 'ps_group_id')]
    private Collection $groups;

    // --- getters / helpers ---
    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getPrice(): float { return $this->price; }
    public function getStrCode(): ?string { return $this->strCode; }
    public function getPriority(): int { return $this->priority; }
    public function getSortOrder(): ?int { return $this->sortOrder; }
    public function isVisible(): bool { return (bool)$this->isVisible; }
    public function isPeriodic(): bool { return (bool)$this->isPeriodic; }
    public function isSelfService(): bool { return $this->selfService === '1'; }
    public function isAdminOnly(): bool { return ($this->prodAdmin ?? 'Y') === 'Y'; }
    public function addMode(ProdServMode $mode): void {
        $this->modes[] = $mode;
    }
    public function addUserMode(UserServMode $mode): void {
        $this->userModes[] = $mode;
    }
    public function getModes(): array { return $this->modes; }
    public function getUserModes(): array { return $this->userModes; }

    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }

    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(PsGroup $group): void
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
        }
    }

    public function removeGroup(PsGroup $group): void
    {
        $this->groups->removeElement($group);
    }
    public function hasGroup(PsGroup $group): bool
    {
        return $this->groups->contains($group);
    }

    public function hasGroupByCode(string $code): bool
    {
        foreach ($this->groups as $group) {
            if ($group->getCode() === $code) {
                return true;
            }
        }

        return false;
    }
}
