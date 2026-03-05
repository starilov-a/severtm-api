<?php declare(strict_types=1);

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'discounts')]
class Discount
{
    #[ORM\Id]
    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'discount')]
    #[ORM\JoinColumn(name: 'disc_uid', referencedColumnName: 'id', nullable: false)]
    private User $user;

    #[ORM\Column(name: 'disc_qnt', type: Types::FLOAT, precision: 16, scale: 4, options: ['default' => 0])]
    private float $quantity = 0.0;

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): void
    {
        $this->quantity = $quantity;
    }
}
