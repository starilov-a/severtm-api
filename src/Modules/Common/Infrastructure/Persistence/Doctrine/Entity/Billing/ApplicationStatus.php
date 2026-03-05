<?php declare(strict_types=1);

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'app_statuses')]
class ApplicationStatus
{
    #[ORM\Id]
    #[ORM\Column(name: 'status_value', type: Types::STRING, length: 32)]
    private string $value;

    #[ORM\Column(name: 'status_name', type: Types::STRING, length: 255)]
    private string $name;

    #[ORM\Column(name: 'deleted', type: Types::INTEGER)]
    private int $deleted = 0;

    public function getValue(): string
    {
        return $this->value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isDeleted(): bool
    {
        return (bool)$this->deleted;
    }
}