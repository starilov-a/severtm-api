<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\ProdServMode;
use App\Modules\Common\Domain\Entity\ProdServModeCost;
use App\Modules\Common\Domain\Entity\UserServMode;

/**
 * Сервис расчёта стоимости режима услуги с учётом influence
 * (аналоги web_get_days_left_influence* и формул из wa_user_add_service).
 */
class UserServModePriceService
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
    public function calculatePayableForUserServMode(UserServMode $userServMode): int
    {
        (int)$influence = $this->calculateInfluence(new \DateTimeImmutable());
        (int)$amount = $userServMode->getMode()->getProdServModeCost()->getCost() * $userServMode->getUnits();
        (int)$payable = $amount * $influence;

        return $payable;
    }

    public function calculateAmountForUserServMode(UserServMode $userServMode): int
    {
        return $userServMode->getMode()->getProdServModeCost()->getCost() * $userServMode->getUnits();
    }

    /**
     * Полный аналог web_get_days_left_influence(__date).
     */
    public function calculateInfluence(\DateTimeInterface $date = new \DateTimeImmutable): float
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

    /**
     * Аналог web_get_days_left_influence_refund(__date).
     */
    public function calculateRefundInfluence(\DateTimeInterface $date = new \DateTimeImmutable): float
    {
        $lastDay = (new \DateTimeImmutable($date->format('Y-m-t')))->setTime(0, 0, 0);
        $daysLeft = $lastDay->diff($date)->days;
        $daysInMonth = (int) $lastDay->format('j');

        if ($daysInMonth <= 0) {
            return 0.0;
        }

        return $daysLeft / $daysInMonth;
    }

    /**
     * Аналог web_get_days_left_influence_writeoff(__date).
     */
    public function calculateWriteOffInfluence(\DateTimeInterface $date = new \DateTimeImmutable): float
    {
        $lastDay = (new \DateTimeImmutable($date->format('Y-m-t')))->setTime(0, 0, 0);
        $daysLeft = $lastDay->diff($date)->days + 1;
        $daysInMonth = (int) $lastDay->format('j');

        if ($daysInMonth <= 0) {
            return 0.0;
        }

        return $daysLeft / $daysInMonth;
    }
}

