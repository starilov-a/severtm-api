<?php

namespace App\Modules\UserCabinet\Application\UseCase\Payment;

use App\Modules\Common\Infrastructure\Exception\BusinessException;

class GetPaymentLinkUseCase
{
    const PAYMENT_LINK_BY_DISTRICT = [
        '1001' => 'https://novtele.ru/oplata/',
        '1013' => 'https://chetelecom.ru/oplata/',
        '1022' => 'https://izet.ru/oplata/',
        '1023' => 'https://izet.ru/oplata/',
        '1024' => 'https://izet.ru/oplata/',
        '1025' => 'https://izet.ru/oplata/',
        '1026' => 'https://izet.ru/oplata/',
        '1050' => 'https://yartele.com/oplata/',
        '1051' => 'https://yartele.com/oplata/',
        '1052' => 'https://yartele.com/oplata/',
        '1053' => 'https://izet.ru/oplata/',
    ];
    public function handle(int $district_id)
    {
        if (!isset(self::PAYMENT_LINK_BY_DISTRICT[$district_id]))
            throw new BusinessException('Регион пользователя не определен');

        return self::PAYMENT_LINK_BY_DISTRICT[$district_id];
    }
}