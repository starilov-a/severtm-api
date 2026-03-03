<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Domain\Entity;

use App\Modules\UserCabinet\Domain\Repository\RegionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegionRepository::class)]
#[ORM\Table(name: 'regions')]
#[ORM\UniqueConstraint(name: 'ux_str_code', columns: ['str_code'])]
class Region
{
    // В БД PK не автоинкремент — задаём вручную
    #[ORM\Id]
    #[ORM\Column(name: 'region_id', type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(name: 'region_name', type: Types::STRING, length: 255)]
    private string $name;

    #[ORM\Column(name: 'str_code', type: Types::STRING, length: 20, nullable: true)]
    private ?string $strCode = null;

    public function __construct(int $id, string $name, ?string $strCode = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->strCode = $strCode;
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    public function getStrCode(): ?string { return $this->strCode; }
    public function setStrCode(?string $code): self { $this->strCode = $code; return $this; }

    public function __toString(): string
    {
        return $this->name;
    }
}
