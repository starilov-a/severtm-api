<?php

namespace App\Modules\Common\Domain\Entity;

use App\Modules\Common\Domain\Repository\ProdDiscountTempRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProdDiscountTempRepository::class)]
#[ORM\Table(name: 'prod_discount_temp')]
class ProdDiscountTemp
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: Types::INTEGER, options: ['unsigned' => true])]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    protected int $id;

    #[ORM\Column(name: 'uid', type: Types::INTEGER, options: ['unsigned' => true, 'default' => 0])]
    protected int $uid = 0;

    #[ORM\Column(name: 'prod_code', type: Types::INTEGER, options: ['unsigned' => true, 'default' => 0])]
    protected int $prodCode = 0;

    #[ORM\Column(name: 'srvmodecost_id', type: Types::INTEGER, options: ['default' => 0])]
    protected int $srvmodecostId = 0;

    #[ORM\Column(name: 'qnt', type: Types::FLOAT, precision: 16, scale: 4, options: ['default' => 0])]
    protected float $qnt = 0.0;

    #[ORM\Column(name: 'number', type: Types::FLOAT, precision: 16, scale: 4, options: ['default' => 0])]
    protected float $number = 0.0;

    /** UNIX‑timestamp */
    #[ORM\Column(name: 'discount_date', type: Types::INTEGER, options: ['unsigned' => true, 'default' => 0])]
    protected int $discountDate = 0;

    #[ORM\Column(name: 'master', type: Types::STRING, length: 32, nullable: true)]
    protected ?string $master = null;

    #[ORM\Column(name: 'prod_comments', type: Types::STRING, length: 255, options: ['default' => ''])]
    protected string $prodComments = '';

    #[ORM\Column(name: 'bill_before', type: Types::FLOAT, precision: 16, scale: 4, nullable: true)]
    protected ?float $billBefore = null;

    #[ORM\Column(name: 'bill_after', type: Types::FLOAT, precision: 16, scale: 4, nullable: true)]
    protected ?float $billAfter = null;

    #[ORM\Column(name: 'prod_status', type: Types::BOOLEAN, nullable: true)]
    protected ?bool $prodStatus = null;

    #[ORM\Column(name: 'upid', type: Types::INTEGER, nullable: true)]
    protected ?int $upid = null;

    public function getId(): int
    {
        return $this->id;
    }
}
