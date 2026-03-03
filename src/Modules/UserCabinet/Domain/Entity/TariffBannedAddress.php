<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Domain\Entity;

use App\Modules\UserCabinet\Domain\Repository\TariffBannedAddressRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TariffBannedAddressRepository::class)]
#[ORM\Table(name: 'tariff_banned_addresses', options: ['charset' => 'cp1251'])]
#[ORM\Index(name: 'fk_t_ban_addr_ref_t_cur', columns: ['tariff_id'])]
class TariffBannedAddress
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Address::class, inversedBy: 'bannedTariffs')]
    #[ORM\JoinColumn(name: 'address_id', referencedColumnName: 'address_id', nullable: false)]
    private Address $address;

    /**
     * В БД tariff_id ссылается на `tariffs_current.tid`.
     */
    #[ORM\Id]
    #[ORM\Column(name: 'tariff_id', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $tariffTid;

    public function __construct(Address $address, int $tariffTid)
    {
        $this->address = $address;
        $this->tariffTid = $tariffTid;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): self
    {
        $this->address = $address;
        return $this;
    }

    public function getTariffTid(): int
    {
        return $this->tariffTid;
    }

    public function setTariffTid(int $tariffTid): self
    {
        $this->tariffTid = $tariffTid;
        return $this;
    }
}
