<?php declare(strict_types=1);

namespace App\Modules\Common\Domain\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'addresses')]
#[ORM\Index(name: 'idx_address_disrt', columns: ['address_district'])]
#[ORM\Index(name: 'idx_address_unit_id', columns: ['unit_id'])]
class Address
{
    /**
     * PK без AUTO_INCREMENT (SMALLINT UNSIGNED). В БД по умолчанию 0 — в коде лучше явно задавать.
     */
    #[ORM\Id]
    #[ORM\Column(name: 'address_id', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $id;

    #[ORM\Column(name: 'address_name', type: Types::STRING, length: 255)]
    private string $name;

    #[ORM\ManyToOne(targetEntity: District::class)]
    #[ORM\JoinColumn(name: 'address_district', referencedColumnName: 'district_id', nullable: false, onDelete: 'CASCADE')]
    private District $district;

    /**
     * Судя по названию, тут может быть список/строка подсетей.
     * Пока строка 255. Если это JSON — скажи, поменяю на json.
     */
    #[ORM\Column(name: 'address_networks', type: Types::STRING, length: 255)]
    private string $networks;

    #[ORM\Column(name: 'unit_id', type: Types::INTEGER, nullable: true, options: ['default' => 1])]
    private ?int $unitId = 1;

    #[ORM\Column(name: 'address_mail_index', type: Types::STRING, length: 6, nullable: true)]
    private ?string $postalIndex = null;

    public function __construct(
        int $id,
        string $name,
        District $district,
        string $networks,
        ?int $unitId = 1,
        ?string $postalIndex = null
    ) {
        $this->id          = $id;
        $this->name        = $name;
        $this->district    = $district;
        $this->networks    = $networks;
        $this->unitId      = $unitId;
        $this->postalIndex = $postalIndex;
    }

    public function getId(): int { return $this->id; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    public function getDistrict(): District { return $this->district; }

    public function setDistrict(District $district): self { $this->district = $district; return $this; }

    public function getRegion(): Region
    {
        // shortcut для удобства
        return $this->district->getRegion();
    }
    public function getNetworks(): string { return $this->networks; }
    public function setNetworks(string $networks): self { $this->networks = $networks; return $this; }

    public function getUnitId(): ?int { return $this->unitId; }
    public function setUnitId(?int $unitId): self { $this->unitId = $unitId; return $this; }

    public function getPostalIndex(): ?string { return $this->postalIndex; }
    public function setPostalIndex(?string $index): self { $this->postalIndex = $index; return $this; }

}
