<?php

namespace App\Modules\UserCabinet\Service\Dto\Validator;
use Symfony\Component\Validator\Constraints as Assert;

class EnableFreezeValidatorDto extends ValidatorDto
{
    #[Assert\NotBlank(message: 'Дата заморозки обязательна')]
    #[Assert\GreaterThan(
        value: 'today',
        message: 'Дата заморозки должна быть в будущем'
    )]
    public string $startDate;

    #[Assert\NotBlank(message: 'Причина обязательна')]
    public int $reason_id;
}