<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Domain\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(readOnly: true)]
#[ORM\Table(name: 'web_log')]
#[ORM\Index(name: 'web_log__uid', columns: ['uid'])]
#[ORM\Index(name: 'web_log__act_id', columns: ['act_id'])]
#[ORM\Index(name: 'idx_wlog_act_time', columns: ['act_time'])]
class WebHistory
{
    /** Составной идентификатор: uid + act_time + act_id + ip */
    #[ORM\Id]
    #[ORM\Column(name: 'uid', type: Types::INTEGER)]
    private int $userId;

    #[ORM\Id]
    #[ORM\Column(name: 'act_time', type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $time;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: WebAction::class)]
    #[ORM\JoinColumn(name: 'act_id', referencedColumnName: 'id', nullable: false, onDelete: 'RESTRICT')]
    private WebAction $action;

    #[ORM\Id]
    #[ORM\Column(name: 'ip', type: Types::STRING, length: 16)]
    private string $ip;

    #[ORM\Column(name: 'act_message', type: Types::TEXT)]
    private string $message;

    #[ORM\Column(name: 'act_result', type: Types::INTEGER, options: ['default' => 0])]
    private int $result = 0;

    // getters
    public function getUserId(): int { return $this->userId; }
    public function getTime(): \DateTimeImmutable { return $this->time; }
    public function getAction(): WebAction { return $this->action; }
    public function getIp(): string { return $this->ip; }
    public function getMessage(): string { return $this->message; }
    public function getResult(): int { return $this->result; }
}
