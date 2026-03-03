<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Domain\Entity;

use App\Modules\UserCabinet\Domain\Repository\UserJurStateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserJurStateRepository::class)]
#[ORM\Table(name: 'entity')]
class UserJurState
{
    #[ORM\Id]
    #[ORM\Column(name: 'entity_id', type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(name: 'entity_code', type: Types::STRING, length: 64)]
    private string $code;

    #[ORM\Column(name: 'description', type: Types::STRING, length: 255, nullable: true)]
    private ?string $description = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
