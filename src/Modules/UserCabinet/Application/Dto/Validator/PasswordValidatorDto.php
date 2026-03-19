<?php

namespace App\Modules\UserCabinet\Application\Dto\Validator;

use App\Modules\Common\Application\Dto\ValidatorDto;
use Symfony\Component\Validator\Constraints as Assert;

class PasswordValidatorDto extends ValidatorDto
{

    #[Assert\NotBlank(message: "Старый пароль не должен быть пустым")]
    public ?string $old_password = null;


    #[Assert\NotBlank(message: "Пароль не должен быть пустым")]
    #[Assert\Length(
        min: 8,
        minMessage: "Пароль должен содержать минимум {{ limit }} символов"
    )]
    #[Assert\Regex(
        pattern: '/^(?=.*\d)[a-zA-Z\d]+$/',
        message: "Пароль должен содержать только латинские буквы и цифры, и обязательно включать хотя бы одну цифру"
    )]

    public ?string $password;

    #[Assert\NotBlank(message: "Подтверждение пароля не должно быть пустым")]
    #[Assert\EqualTo(
        propertyPath: 'password',
        message: 'Пароли не совпадают'
    )]
    public ?string $password_confirmation;
}
