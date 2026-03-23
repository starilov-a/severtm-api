<?php declare(strict_types=1);

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'contract_change_type')]
class ContractChangeType
{
    #[ORM\Id]
    #[ORM\Column(name: 'code_str', type: Types::STRING, length: 32)]
    private string $code;

    #[ORM\OneToMany(
        mappedBy: 'type',
        targetEntity: ContractChangeHistory::class
    )]
    private Collection $histories;

    public function __construct(string $code)
    {
        $this->code = $code;
        $this->histories = new ArrayCollection();
    }

    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return ContractChangeHistory[]
     */
    public function getHistories(): array
    {
        return $this->histories->toArray();
    }
}
