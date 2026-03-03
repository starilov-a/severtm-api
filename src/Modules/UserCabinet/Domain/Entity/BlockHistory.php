<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Domain\Entity;

use App\Modules\UserCabinet\Domain\Repository\BlockHistoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlockHistoryRepository::class)]
#[ORM\Table(name: 'block_history')]
#[ORM\Index(name: 'block_uid', columns: ['block_uid'])]
#[ORM\Index(name: 'block_date', columns: ['block_date'])]
#[ORM\Index(name: 'block_who', columns: ['block_who'])]
class BlockHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'block_uid', referencedColumnName: 'id', nullable: false)]
    private User $user;

    #[ORM\Column(name: 'block_date', type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeInterface $blockDate;

    #[ORM\Column(name: 'block_old', type: Types::SMALLINT, nullable: true)]
    private ?int $blockOld = null;

    #[ORM\Column(name: 'block_status', type: Types::SMALLINT, nullable: true)]
    private ?int $blockStatus = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'block_who', referencedColumnName: 'id', nullable: true)]
    private User $master;

    #[ORM\Column(name: 'block_comments', type: Types::STRING, length: 255, nullable: true)]
    private ?string $blockComments = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getBlockDate(): \DateTimeInterface
    {
        return $this->blockDate;
    }

    public function setBlockDate(\DateTimeInterface $blockDate): self
    {
        $this->blockDate = $blockDate;

        return $this;
    }

    public function getBlockOld(): ?int
    {
        return $this->blockOld;
    }

    public function setBlockOld(?int $blockOld): self
    {
        $this->blockOld = $blockOld;

        return $this;
    }

    public function getBlockStatus(): ?int
    {
        return $this->blockStatus;
    }

    public function setBlockStatus(?int $blockStatus): self
    {
        $this->blockStatus = $blockStatus;

        return $this;
    }

    public function getMaster(): User
    {
        return $this->master;
    }

    public function setMaster(User $master): self
    {
        $this->master = $master;

        return $this;
    }

    public function getBlockComments(): ?string
    {
        return $this->blockComments;
    }

    public function setBlockComments(?string $blockComments): self
    {
        $this->blockComments = $blockComments;

        return $this;
    }
}

