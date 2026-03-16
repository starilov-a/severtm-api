<?php

namespace App\Modules\JurManagerCabinet\Domain\RepositoryInterface;

use App\Modules\JurManagerCabinet\Domain\Entity\Contract\Contract;
use App\Modules\JurManagerCabinet\Domain\Entity\Reissue\ContractReissueSettings;

interface ContractSettingsRepositoryInterface
{
    public function loadForReissue(Contract $contract): ContractReissueSettings;

    public function applyForReissue(Contract $contract, ContractReissueSettings $settings): void;
}