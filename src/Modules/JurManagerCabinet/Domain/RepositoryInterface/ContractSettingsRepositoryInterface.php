<?php

namespace App\Modules\JurManagerCabinet\Domain\RepositoryInterface;

use App\Modules\JurManagerCabinet\Domain\Entity\Contract;
use App\Modules\JurManagerCabinet\Domain\Entity\ContractReissueSettings;

interface ContractSettingsRepositoryInterface
{
    public function loadForReissue(Contract $contract): ContractReissueSettings;

    public function applyForReissue(Contract $contract, ContractReissueSettings $settings): void;
}