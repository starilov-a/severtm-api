<?php

namespace App\Modules\UserCabinet\Domain\Service\Definitions\Finances\Payables\Calculators;

use App\Modules\UserCabinet\Domain\Entity\ProdServMode;

class DefaultCalculator
{

    /**
     * Расчёт для сценария добавления услуги в текущем месяце
     * (используется web_get_days_left_influence).
     *
     * @param ProdServMode $costMode   активированный режим услуги
     * @param float        $cost   базовая стоимость одной единицы (prod_serv_mode_costs.cost)
     * @param int          $units  количество единиц (__unit в процедуре)
     * @param \DateTimeInterface|null $date дата, относительно которой считаем influence (now() по умолчанию)
     */
    protected function calculatePayable(int $amount, float $influence): int
    {
        $payable = $amount * $influence;

        return (int)$payable;
    }

    protected  function calculateAmount(int $cost, int $units): int
    {
        return $cost * $units;
    }

    /**
     * Полный аналог web_get_days_left_influence(__date).
     */
    protected  function calculateInfluence(\DateTimeInterface $date = new \DateTimeImmutable): float
    {
        // first day of month (00:00)
        $firstDay = (new \DateTimeImmutable($date->format('Y-m-01')))->setTime(0, 0, 0);
        // last day of month (00:00)
        $lastDay = (new \DateTimeImmutable($date->format('Y-m-t')))->setTime(0, 0, 0);

        $daysLeft = $lastDay->diff($date)->days;
        $days = $lastDay->diff($firstDay)->days + 1;

        if ($days > 0 && $days > $daysLeft) {
            return $daysLeft / $days;
        }

        return 1.0;
    }
}