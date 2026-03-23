<?php

namespace App\Modules\BuildermanCabinet\Domain\Service;

use App\Modules\BuildermanCabinet\Domain\Entity\ApplicationStatus;
use App\Modules\BuildermanCabinet\Domain\Policy\ApplictionStatus\ApplicationStatusesAvailableForBuilderListPolicy;
use App\Modules\BuildermanCabinet\Domain\RepositoryInterface\ApplicationStatusRepositoryInterface;

class ApplicationStatusService
{
    public function __construct(
        protected ApplicationStatusRepositoryInterface $appStatusRepo,
        // protected ApplicationStatusesAvailableForBuilderListPolicy $policy
    ) {}


    /**
     * @return array<ApplicationStatus>
     */
    public function getStatusesForBuilderList(): array
    {
        $allStatuses = $this->appStatusRepo->findAll();

        $statusesForList = [];
        foreach ($allStatuses as $status)
            if (in_array($status->getStrCode(), ['connection', 'transfer', 'uncallable', 'uncalled', 'problem']))
                $statusesForList[] = $status;

        // Либо сделать бизнес проверку через политику
        // if ($this->policy->isAllowed(new ApplicationStatusContext($status)))
        //     $statusesForList[] = $status;

        return $statusesForList;
    }
}