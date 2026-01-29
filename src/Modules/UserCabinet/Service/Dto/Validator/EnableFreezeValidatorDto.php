<?php

namespace App\Modules\UserCabinet\Service\Dto\Validator;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class EnableFreezeValidatorDto extends ValidatorDto
{
    public string $startDate;
    #[Assert\NotBlank(message: 'Причина обязательна')]
    public int $reason_id;

    #[Assert\Callback]

    public function validateStartDate(ExecutionContextInterface $context): void
    {
        if (empty($this->startDate)) {
            $context->buildViolation('Дата заморозки обязательна')
                ->atPath('startDate')
                ->addViolation();
            return;
        }

        $date = \DateTimeImmutable::createFromFormat('d.m.Y', $this->startDate);

        // формат не совпал
        if (!$date) {
            $context->buildViolation('Дата должна быть в формате ДД.ММ.ГГГГ')
                ->atPath('startDate')
                ->addViolation();
            return;
        }

        // дата должна быть строго в будущем
        if ($date <= new \DateTimeImmutable('today')) {
            $context->buildViolation('Дата заморозки должна быть в будущем')
                ->atPath('startDate')
                ->addViolation();
        }
    }
}