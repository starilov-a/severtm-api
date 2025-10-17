# Сервис логирования (Symfony)

Лёгкий сервис с разделением на два типа логов:
- **бизнес-логи** (действия пользователя, аудиты, события UX),
- **логи ошибок** (ошибки и исключения приложения).

Архитектура построена на интерфейсах и «адаптерах» (Strategy): выбираешь/меняешь способ записи логов через DI, не трогая бизнес-код.

## Состав

```
Logger/
├─ LoggerService.php                         # Фасад: единая точка входа
├─ BusinessLoggerInterface.php               # Контракт для бизнес-логов
├─ ErrorLoggerInterface.php                  # Контракт для логов ошибок
├─ Dto/
│  ├─ BusinessLogDto.php                     # DTO бизнес-лога
│  └─ ErrorLogDto.php                        # DTO лога ошибки (пока пустой)
└─ Adapters/
   ├─ WebLogAdapter.php                      # Адаптер: запись в таблицу web_log (Doctrine DBAL)
   └─ ErrorLogAdapter.php                    # Заглушка для ошибок (дополни под себя)
```

### Ключевые классы

- `LoggerService`  
  Сервис с двумя методами:
  ```php
  businessLog(BusinessLogDto $log): void
  errorLog(ErrorLogDto $log): void
  ```

- `BusinessLoggerInterface`  
  Контракт для любых реализаций бизнес-логгера:
  ```php
  public function businessLog(BusinessLogDto $log): void;
  ```

- `ErrorLoggerInterface`  
  Контракт для логгера ошибок:
  ```php
  public function logError(ErrorLogDto $log): void;
  ```

- `BusinessLogDto`  
  Поля:
  ```php
  __construct(
      public int $userId,
      public int $actionId,
      public string $message,
      public bool $ok,
      public ?string $ip = null,
      public array $context = [],
      public $when = new \DateTime('now'),
  )
  ```

## Установка и регистрация в Symfony

### 1) Сервисы (services.yaml)

```yaml
services:
  _defaults:
    autowire: true
    autoconfigure: true

  App\Modules\Common\Infrastructure\Service\Logger\LoggerService: ~
  App\Modules\Common\Infrastructure\Service\Logger\Adapters\WebLogAdapter: ~
  App\Modules\Common\Infrastructure\Service\Logger\Adapters\ErrorLogAdapter: ~

  App\Modules\Common\Infrastructure\Service\Logger\BusinessLoggerInterface:
    alias: App\Modules\Common\Infrastructure\Service\Logger\Adapters\WebLogAdapter

  App\Modules\Common\Infrastructure\Service\Logger\ErrorLoggerInterface:
    alias: App\Modules\Common\Infrastructure\Service\Logger\Adapters\ErrorLogAdapter
```

## Использование

```php

$this->loggerService->businessLog(
    new BusinessLogDto(
        $user->getId(), 
        $webAction->getId(), 
        'Пользователь ' . $user->getId() . ' успешно сменил тариф('. $newNextTariff->getId() .')' , 
        true
    )
);

```

## Как добавить свой адаптер

### Пример 1. Бизнес-логи в новую таблицу

```php
final class NewTableBusinessAdapter implements BusinessLoggerInterface
{
    public function __construct(private EntityManagerInterface $em) {}

    public function businessLog(BusinessLogDto $log): void
    {
        $this->em->getConnection()->insert('business_logs', [
            'user_id'  => $log->userId,
            'action'   => $log->actionId,
            'message'  => $log->message,
            'ok'       => $log->ok ? 1 : 0,
            'ip'       => $log->ip,
            'payload'  => json_encode($log->context, JSON_UNESCAPED_UNICODE),
            'created_at' => $log->when->format('Y-m-d H:i:s'),
        ]);
    }
}
```

### Пример 2. Через Monolog

```php
final class MonologBusinessAdapter implements BusinessLoggerInterface
{
    public function __construct(private LoggerInterface $logger) {}

    public function businessLog(BusinessLogDto $log): void
    {
        $this->logger->info($log->message, [
            'userId'  => $log->userId,
            'action'  => $log->actionId,
            'ok'      => $log->ok,
            'ip'      => $log->ip,
            'context' => $log->context,
            'when'    => $log->when->format(DATE_ATOM),
        ]);
    }
}
```

### Пример 3. Логи ошибок в Sentry

```php
final class SentryErrorAdapter implements ErrorLoggerInterface
{
    public function __construct(private \Sentry\State\HubInterface $hub) {}

    public function logError(ErrorLogDto $log): void
    {
        $this->hub->captureException($log->throwable);
    }
}
```

## Почему так

- **Фасад (`LoggerService`)** → один вход для всего кода.
- **Интерфейсы** → легко сменить реализацию без изменений кода.
- **DTO** → строгая схема данных.
- **Адаптеры** → любая цель записи: БД, Monolog, Sentry, ELK и т. д.