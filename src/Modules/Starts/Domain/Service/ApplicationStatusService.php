<?php

namespace App\Modules\Starts\Domain\Service;

use App\Modules\Starts\Domain\Context\Definitions\Application\ApplicationStatusContext;
use App\Modules\Starts\Domain\Entity\ApplicationStatus;
use App\Modules\Starts\Domain\Policy\ApplictionStatus\ApplicationStatusesAvailableForBuilderListPolicy;
use App\Modules\Starts\Domain\RepositoryInterface\ApplicationStatusRepositoryInterface;

class ApplicationStatusService
{
    public function __construct(
        protected ApplicationStatusRepositoryInterface $appStatusRepo,
        protected ApplicationStatusesAvailableForBuilderListPolicy $policy
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