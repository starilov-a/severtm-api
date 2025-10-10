<?php


namespace App\Modules\UserCabinet\Service;

use App\Modules\Common\Infrastructure\Service\UserSessionService;
use App\Modules\UserCabinet\Entity\WebAction;
use App\Modules\UserCabinet\Repository\WebHistoryRepository;

class WebHistoryService
{
    protected WebHistoryRepository $webHistoryRepo;
    public function __construct(
        WebHistoryRepository $webHistoryRepo
    )
    {
        $this->webHistoryRepo = $webHistoryRepo;
    }
    public function writeWebLog(int $uid, WebAction $action, string $message, bool $success)
    {
        $ip = UserSessionService::getUserIp();
        $this->webHistoryRepo->log($uid, $action->getId(), $message, $success, $ip);
    }
}
