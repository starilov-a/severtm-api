<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Domain\Entity;

use App\Modules\UserCabinet\Domain\Repository\BlockStateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlockStateRepository::class)]
#[ORM\Table(name: 'block_states')]
#[ORM\UniqueConstraint(name: 'uidx_block_states_str_code', columns: ['str_code'])]
class BlockState
{
    #[ORM\Id]
    #[ORM\Column(name: 'block_id', type: Types::SMALLINT)]
    private int $id;

    #[ORM\Column(name: 'str_code', type: Types::STRING, length: 32)]
    private string $code;

    #[ORM\Column(name: 'block_name', type: Types::STRING, length: 128)]
    private string $name;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
