<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Domain\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Modules\UserCabinet\Domain\Repository\WebActionRepository::class)]
#[ORM\Table(name: 'web_actions')]
#[ORM\Index(name: 'web_ac__visible', columns: ['visible'])]
class WebAction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(name: 'function', type: Types::STRING, length: 64, nullable: true)]
    private ?string $function = null;

    #[ORM\Column(name: 'visible', type: Types::BOOLEAN)]
    private bool $visible = true;

    #[ORM\Column(name: 'cid', type: Types::STRING, length: 64)]
    private string $cid;

    #[ORM\Column(name: 'proc', type: Types::STRING, length: 64)]
    private string $proc;

    #[ORM\Column(name: 'name', type: Types::STRING, length: 64)]
    private string $name;

    #[ORM\Column(name: 'type', type: Types::STRING, length: 20)]
    private string $type;

    #[ORM\Column(name: 'left_key', type: Types::INTEGER)]
    private int $leftKey;

    #[ORM\Column(name: 'right_key', type: Types::INTEGER)]
    private int $rightKey;

    #[ORM\Column(name: 'level', type: Types::INTEGER)]
    private int $level;

    #[ORM\Column(name: 'allow_organization', type: Types::BOOLEAN, options: ['default' => 0])]
    private bool $allowOrganization = false;

    // getters
    public function getId(): int { return $this->id; }
    public function getCid(): string { return $this->cid; }
    public function getName(): string { return $this->name; }
    public function isVisible(): bool { return $this->visible; }
}
