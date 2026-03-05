<?php declare(strict_types=1);

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'application_types')]
class ApplicationType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'at_id', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $id;

    #[ORM\Column(name: 'at_name', type: Types::STRING, length: 100)]
    private string $name;

    #[ORM\Column(name: 'at_strcode', type: Types::STRING, length: 50)]
    private string $strCode;

    #[ORM\Column(name: 'at_visible', type: Types::INTEGER, nullable: true, options: ['unsigned' => true])]
    private ?int $visible = null;

    // --- getters ---
    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getStrCode(): string { return $this->strCode; }

    public function isVisible(): ?bool
    {
        return $this->visible === null ? null : (bool)$this->visible;
    }
}