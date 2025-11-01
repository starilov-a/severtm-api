<?php

namespace App\Modules\UserCabinet\Service\Dto\Validator;

use Symfony\Component\Validator\Constraints as Assert;

class WebUserValidatorDto
{
    #[Assert\NotBlank(message: "Email не должен быть пустым")]
    #[Assert\Email(message: "Некорректный формат email")]
    public string $email;

    #[Assert\NotBlank(message: "Телефон не должен быть пустым")]
    #[Assert\Length(
        min: 7,
        max: 20,
        minMessage: "Телефон должен содержать минимум {{ limit }} символов",
        maxMessage: "Телефон должен содержать максимум {{ limit }} символов"
    )]
    public string $phone;

    public string $comment;

}
