<?php

namespace App\Modules\JurManagerCabinet\Infrastructure\Persistence\Doctrine\Repository\Mappers;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserTask;
use App\Modules\JurManagerCabinet\Domain\Entity\Reissue\ContractReissueProcess;

class ContractReissueProcessMapper
{
    static public function map(UserTask $tableUserTask, array $tableEnumParams): ContractReissueProcess
    {


        return  new ContractReissueProcess(
            $tableEnumParams['']
        );
    }
}