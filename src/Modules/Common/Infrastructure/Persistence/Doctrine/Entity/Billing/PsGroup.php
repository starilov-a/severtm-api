<?php declare(strict_types=1);

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'ps_groups')]
class PsGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'ps_group_id', type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(name: 'ps_group_name', type: Types::STRING, length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(name: 'ps_grp_code', type: Types::STRING, length: 50, nullable: true, unique: true)]
    private ?string $code = null;

    #[ORM\Column(name: 'user_visible', type: Types::INTEGER)]
    private int $userVisible = 0;

    #[ORM\ManyToMany(targetEntity: ProductService::class, mappedBy: 'groups')]
    private Collection $services;

    public function __construct()
    {
        $this->services = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function isUserVisible(): bool
    {
        return $this->userVisible === 1;
    }

    public function setUserVisible(int $userVisible): self
    {
        $this->userVisible = $userVisible;
        return $this;
    }

    /**
     * @return Collection<int, ProductService>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(ProductService $service): void
    {
        if ($this->services->contains($service)) {
            return;
        }

        $this->services->add($service);
        $service->addGroup($this);
    }

    public function removeService(ProductService $service): void
    {
        if (!$this->services->removeElement($service)) {
            return;
        }

        $service->removeGroup($this);
    }
}
