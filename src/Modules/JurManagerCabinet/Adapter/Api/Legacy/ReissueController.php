<?php

namespace App\Modules\JurManagerCabinet\Adapter\Api\Legacy;

use App\Modules\Common\Adapter\Api\Controller;
use App\Modules\JurManagerCabinet\Application\Dto\Request\Reissue\ReissueContractDto;
use App\Modules\JurManagerCabinet\Application\UseCase\Reissue\ScheduleReissueContractUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ReissueController extends Controller
{
    public function authenticate(): bool
    {
        return false;
    }

    #[Route(
        '/schedule-reissue-contract',
        name: 'scheduleReissueContract',
        methods: ['POST']
    )]
    public function ScheduleReissueContract(Request $request, ScheduleReissueContractUseCase $useCase): JsonResponse
    {
        $data = !empty($request->getContent()) ? $request->toArray() : [];
        //$this->validate(new ReissueContractValidatorDto(), $data);

        $useCase->execute(new ReissueContractDto(
            $data['contractId'],
            $data['managerId'],
            $data['newInn'],
            $data['dateReissue'],
            $data['fio'],
            $data['login'],
            $data['password'],
            $data['phone'],
            $data['comment']
        ));

        return $this->response(
            true,
            'Создана задача на переоформление'
        );
    }
}