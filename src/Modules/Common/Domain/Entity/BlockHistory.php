<?php declare(strict_types=1);

namespace App\Modules\Common\Domain\Entity;

use App\Modules\Common\Domain\Repository\BlockHistoryRepository;
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

    #[ORM\Column(name: 'block_uid', type: Types::INTEGER, options: ['unsigned' => true])]
    private int $blockUid;

    #[ORM\Column(name: 'block_date', type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeInterface $blockDate;

    #[ORM\Column(name: 'block_old', type: Types::SMALLINT, nullable: true)]
    private ?int $blockOld = null;

    #[ORM\Column(name: 'block_status', type: Types::SMALLINT, nullable: true)]
    private ?int $blockStatus = null;

    #[ORM\Column(name: 'block_who', type: Types::STRING, length: 20, nullable: true)]
    private ?string $blockWho = null;

    #[ORM\Column(name: 'block_comments', type: Types::STRING, length: 255, nullable: true)]
    private ?string $blockComments = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getBlockUid(): int
    {
        return $this->blockUid;
    }

    public function setBlockUid(int $blockUid): self
    {
        $this->blockUid = $blockUid;

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

    public function getBlockWho(): ?string
    {
        return $this->blockWho;
    }

    public function setBlockWho(?string $blockWho): self
    {
        $this->blockWho = $blockWho;

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

