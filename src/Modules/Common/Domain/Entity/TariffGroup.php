<?php declare(strict_types=1);

namespace App\Modules\Common\Domain\Entity;

use App\Modules\Common\Domain\Repository\TariffGroupRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TariffGroupRepository::class)]
#[ORM\Table(name: 'tariffs_groups')]
#[ORM\UniqueConstraint(name: 'uidx_tg_grp_code', columns: ['tariffs_grp_code'])]
class TariffGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'tariffs_group_id', type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(name: 'tariffs_group_name', type: Types::STRING, length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(name: 'tariffs_grp_code', type: Types::STRING, length: 50, nullable: true)]
    private ?string $code = null; // уникален по БД

    #[ORM\Column(name: 'user_visible', type: Types::INTEGER)]
    private int $userVisible = 0;

    public function getId(): int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function getCode(): ?string { return $this->code; }
    public function getUserVisible(): int { return $this->userVisible; }

    public function setName(?string $name): void { $this->name = $name; }
    public function setCode(?string $code): void { $this->code = $code; }
    public function setUserVisible(int $userVisible): void { $this->userVisible = $userVisible; }
}
