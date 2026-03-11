<?php

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'contract_change_history_params')]
class ContractChangeHistoryParam
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: ContractChangeHistory::class)]
    #[ORM\JoinColumn(name: 'history_id', referencedColumnName: 'history_id', nullable: false)]
    private ContractChangeHistory $history;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: EnumParameter::class)]
    #[ORM\JoinColumn(name: 'parameter', referencedColumnName: 'param_code', nullable: false)]
    private EnumParameter $parameter;

    #[ORM\Column(name: 'value_after', type: Types::TEXT, nullable: true)]
    private ?string $valueAfter = null;

    #[ORM\Column(name: 'value_before', type: Types::TEXT, nullable: true)]
    private ?string $valueBefore = null;

    public function setHistory(ContractChangeHistory $history): void { $this->history = $history; }
    public function setParameter(EnumParameter $parameter): void { $this->parameter = $parameter; }
    public function setValueAfter(?string $valueAfter): void { $this->valueAfter = $valueAfter; }
    public function setValueBefore(?string $valueBefore): void { $this->valueBefore = $valueBefore; }
}
