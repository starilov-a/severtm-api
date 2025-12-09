<?php declare(strict_types=1);

namespace App\Modules\Common\Domain\Entity;

use App\Modules\UserCabinet\Repository\DistrictRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DistrictRepository::class)]
#[ORM\Table(name: 'districts')]
#[ORM\Index(name: 'fk_dst_ref_regs', columns: ['region_id'])]
class District
{
    #[ORM\Id]
    #[ORM\Column(name: 'district_id', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $id;

    #[ORM\Column(name: 'district_name', type: Types::STRING, length: 255)]
    private string $name;

    #[ORM\Column(name: 'district_networks', type: Types::TEXT, nullable: true)]
    private ?string $networks = null;

    /**
     * @var Region
     *
     * district.region_id → regions.region_id
     */
    #[ORM\ManyToOne(targetEntity: Region::class)]
    #[ORM\JoinColumn(name: 'region_id', referencedColumnName: 'region_id', nullable: false, onDelete: 'CASCADE')]
    private Region $region;

    public function __construct(int $id, string $name, Region $region, ?string $networks = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->region = $region;
        $this->networks = $networks;
    }

    public function getId(): int { return $this->id; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    public function getNetworks(): ?string { return $this->networks; }
    public function setNetworks(?string $networks): self { $this->networks = $networks; return $this; }

    public function getRegion(): Region { return $this->region; }
    public function setRegion(Region $region): self { $this->region = $region; return $this; }

    public function __toString(): string
    {
        return $this->name;
    }
}
