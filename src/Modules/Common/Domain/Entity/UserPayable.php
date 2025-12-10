<?php

namespace App\Modules\Common\Domain\Entity;

use App\Modules\Common\Domain\Repository\UserPayableRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserPayableRepository::class)]
#[ORM\Table(name: 'user_payables')]
class UserPayable
{
    #[ORM\Id]
    #[ORM\Column(name: 'upid', type: Types::INTEGER)]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    protected int $id;

    #[ORM\Column(name: 'fid', type: Types::INTEGER)]
    protected int $fid;

    /** users.id */
    #[ORM\Column(name: 'uid', type: Types::INTEGER, options: ['unsigned' => true])]
    protected int $uid;

    /** prod_serv_modes.id */
    #[ORM\Column(name: 'srvmode_id', type: Types::INTEGER)]
    protected int $srvmodeId;

    /** prod_serv_mode_costs.id */
    #[ORM\Column(name: 'srvcost_id', type: Types::INTEGER)]
    protected int $srvcostId;

    #[ORM\Column(name: 'fid_refund', type: Types::INTEGER, options: ['default' => 0])]
    protected int $fidRefund = 0;

    /** user_payables_types.type_id */
    #[ORM\ManyToOne(targetEntity: UserPayableType::class)]
    #[ORM\JoinColumn(name: 'type', referencedColumnName: 'type_id', nullable: false)]
    protected UserPayableType $type;

    #[ORM\Column(name: 'cost', type: Types::DECIMAL, precision: 16, scale: 2, options: ['default' => '0.00'])]
    protected string $cost = '0.00';

    #[ORM\Column(name: 'units', type: Types::SMALLINT, options: ['default' => 1])]
    protected int $units = 1;

    #[ORM\Column(name: 'amount', type: Types::DECIMAL, precision: 16, scale: 2, options: ['default' => '0.00'])]
    protected string $amount = '0.00';

    #[ORM\Column(name: 'discount', type: Types::DECIMAL, precision: 16, scale: 2, options: ['default' => '0.00'])]
    protected string $discount = '0.00';

    #[ORM\Column(name: 'payable', type: Types::DECIMAL, precision: 16, scale: 2, options: ['default' => '0.00'])]
    protected string $payable = '0.00';

    #[ORM\Column(name: 'influence', type: Types::FLOAT, options: ['default' => 1])]
    protected float $influence = 1.0;

    #[ORM\Column(name: 'is_real', type: Types::SMALLINT, options: ['default' => 1])]
    protected int $isReal = 1;

    #[ORM\Column(name: 'is_applied', type: Types::SMALLINT, options: ['default' => 0])]
    protected int $isApplied = 0;

    #[ORM\Column(name: 'created_at', type: Types::DATETIME_IMMUTABLE)]
    protected \DateTimeImmutable $createdAt;

    #[ORM\Column(name: 'usmid', type: Types::INTEGER, options: ['default' => 0])]
    protected int $usmid = 0;

    public function getId(): int
    {
        return $this->id;
    }
}
