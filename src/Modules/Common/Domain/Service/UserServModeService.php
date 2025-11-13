<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Repository\UserServModeRepository;

class UserServModeService
{
    public function __construct(private UserServModeRepository $userServModeRepo){}
    public function getCurrentModesWithService(User $user): array
    {
        $servModes = $this->userServModeRepo->findCurrentModesWithService($user);

        // Группируем по услуге
        $byService = [];
        foreach ($servModes as $mode) {
            $service = $mode->getService();
            $sid = $service->getId();

            if (!isset($byService[$sid])) {
                $byService[$sid] = [
                    'servId' => $sid,
                    'name'   => $service->getName(),
                    'code'   => $service->getStrCode(),
                    'modes'  => [],
                ];
            }

            $byService[$sid]['modes'][] = [
                'modeId' => $mode->getId(),
                'name'   => $mode->getName(),
                'code'   => $mode->getStrCode(),
            ];
        }

        return array_values($byService);
    }
}