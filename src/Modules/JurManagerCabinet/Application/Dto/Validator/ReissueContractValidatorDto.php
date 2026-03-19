<?php

namespace App\Modules\JurManagerCabinet\Application\Dto\Validator;

use App\Modules\Common\Application\Dto\ValidatorDto;
use Symfony\Component\Validator\Constraints as Assert;

class ReissueContractValidatorDto extends ValidatorDto
{
    #[Assert\NotBlank(message: 'ID договора обязателен')]
    #[Assert\Regex(
        pattern: '/^\d{8}$/',
        message: 'contractId должен содержать ровно 8 цифр'
    )]
    public string $contractId;

    #[Assert\NotBlank(message: 'ID менеджера обязателен')]
    #[Assert\Regex(
        pattern: '/^\d{8}$/',
        message: 'managerId должен содержать ровно 8 цифр'
    )]
    public string $managerId;

    public ?string $newInn = null;

    #[Assert\NotBlank(message: 'Дата будущего переоформления обязательна')]
    #[Assert\Date(message: 'dateReissue должен быть корректной датой')]
    public string $dateReissue;

    public ?string $fio = null;

    #[Assert\NotBlank(message: 'Логин обязателен')]
    public string $login;

    #[Assert\NotBlank(message: 'Новый пароль обязателен')]
    public string $password;

    public ?string $phone = null;

    public ?string $comment = null;
}
