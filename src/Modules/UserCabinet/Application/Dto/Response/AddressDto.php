<?php

namespace App\Modules\UserCabinet\Application\Dto\Response;

use App\Modules\UserCabinet\Domain\Entity\Address;
use App\Modules\UserCabinet\Domain\Service\Dto\Dto;

class AddressDto extends Dto
{
    private int $id;
    private string $name;
    private int $districtId;
    private string $networks;
    private ?int $unitId = 1;
    private ?string $postalIndex = null;

    /**
     * @param int $id
     * @param string $name
     * @param int $districtId
     * @param string $networks
     * @param int|null $unitId
     * @param string|null $postalIndex
     */
    public function __construct(Address $address)
    {
        $this->id = $address->getId();
        $this->name = $address->getName();
        $this->districtId = $address->getDistrict()->getId();
        $this->networks = $address->getNetworks();
        $this->unitId = $address->getUnitId();
        $this->postalIndex = $address->getPostalIndex();
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDistrictId(): int
    {
        return $this->districtId;
    }

    public function getNetworks(): string
    {
        return $this->networks;
    }

    public function getUnitId(): ?int
    {
        return $this->unitId;
    }

    public function getPostalIndex(): ?string
    {
        return $this->postalIndex;
    }

}
