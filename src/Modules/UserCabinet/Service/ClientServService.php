<?php

namespace App\Modules\UserCabinet\Service;

class ClientServService
{
    /*
     * Список доступных услуг на подключение клиенту
     * */
    public function listAvailableServices(int $uid): array
    {
        return [];
    }

    /*
     * Получение активных услуг клиента
     * */
    public function getCurrentServices(int $uid): array
    {
        return [];
    }

    /*
     * Активация услуги клиента
     * */
    public function enableService(int $uid, int $serviceId): bool
    {
        return false;
    }

    /*
     * Отключение услуги клиента
     * */
    public function disableService(int $uid, int $serviceId): bool
    {
        return false;
    }

    /*
     * Заморозка услуг клиента
     * */
    public function freezeServices(int $uid): bool
    {
        return false;
    }

    /*
     * Получение отсрочки для клиента
     * */
    public function takeBreak(int $uid): bool
    {
        return false;
    }
}