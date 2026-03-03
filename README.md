## Деплой проекта

Папка `./docker` содержит все необходимые файлы для деплоя/локального запуска в Docker: `docker-compose.yml`, env-шаблон (`.env.dist`), Dockerfile'ы `php-fpm` и `nginx`, а также конфигурации `nginx` и `xdebug`.

### Быстрый старт (Docker)

1. Настройка переменных среды **docker-compose**:
   ```sh
   cp ./docker/.env.dist ./docker/.env
   ```
   - `NGINX_HOST_HTTP_PORT` — внешний HTTP-порт (по умолчанию `80`)
   - `PUID/PGID` — uid/gid пользователя на хосте (чтобы файлы в volume не создавались от root)
   - `INSTALL_XDEBUG` — ставить ли Xdebug в `php-fpm` (по умолчанию `false`)

2. Настройка окружения **Symfony** (минимум):
   ```sh
   cp ./.env.example ./.env
   ```

3. Запуск контейнеров
   - Рекомендуемый вариант (из корня репозитория):
     ```sh
     sudo docker compose --env-file ./docker/.env -f ./docker/docker-compose.yml up -d --build
     ```
   - Альтернатива (перейти в `./docker` и запускать без `--env-file`):
     ```sh
     cd docker
     docker compose up -d --build
     ```

4. Установка зависимостей Composer (внутри контейнера `php-fpm`)
   - **develop**:
     ```sh
     docker compose --env-file ./docker/.env -f ./docker/docker-compose.yml exec -u www-data php-fpm composer install
     ```
   - **production**:
     ```sh
     docker compose --env-file ./docker/.env -f ./docker/docker-compose.yml exec -u www-data php-fpm composer install --no-dev --optimize-autoloader
     ```

### Особенности настройки

1. Настройка **Xdebug** (опционально)
   - Включите установку Xdebug: выставьте `INSTALL_XDEBUG=true` в `./docker/.env` и пересоберите контейнеры.
   - Конфиг Xdebug: `./docker/php-fpm/xdebug.ini` (пример: `./docker/php-fpm/xdebug.ini.example`).
   - PHPStorm: имя сервера должно совпадать с `PHP_IDE_CONFIG` в `./docker/docker-compose.yml` (сейчас `serverName=docker-symfony`).

2. Проверка, что сервис поднялся
   - Приложение доступно на `http://localhost:<NGINX_HOST_HTTP_PORT>` (порт задается в `./docker/.env`).

### Особенности разработки

1. По ссылке `/_profiler` в *dev* среде мы можем отобразить **Symfony Profiler**

## Архитектурные особенности

### Модульность и разделение
Корневая папка `src/Modules` разделена по доменам:

```text
src/Modules
├── Common        <-- ядро и переиспользуемые компоненты
└── UserCabinet   <-- всё, что связано с личным кабинетом
```

- **Common** — единое ядро. Здесь лежат сущности, доменные сервисы, репозитории, движок бизнес‑правил (`Domain`) и инфраструктура (логирование, авторизация, базовые исключения).
- **UserCabinet** — модуль ЛК. Содержит свои контроллеры, сервисы, хранилища и слушатели. За сложные операции он обращается к ядру `Common`, не дублируя функциональность.

При появлении новых модулей (например, `AdminPanel`) они наследуют эти же принципы: собственные контроллеры/DTO + использование ядра.

### Слоистость приложения
В проекте соблюдается последовательность:  
`HTTP (контроллеры модуля)` → `сервисы модуля (транзакции/оркестрация)` → `UseCase (Application слой)` → `Domain сервисы/правила` → `сущности/репозитории` (+ инфраструктура как кросс‑срез).

1. **HTTP/Модульный слой** (`src/Modules/*/Controllers`). Контроллеры принимают HTTP‑запросы, валидируют входные данные/DTO и вызывают сервисы модуля.
2. **Сервисы модуля** (`src/Modules/*/Service`). Здесь задается **граница транзакции** (обычно через `EntityManagerInterface->getConnection()->transactional()`), а также выполняется “склейка” шагов бизнес‑процесса: выбор нужного use-case, подготовка контекстов/DTO, обработка результата.
3. **UseCases (Application слой)** (`src/Modules/Common/Application/UseCase`). Use-case группирует бизнес‑сценарий в одном месте, сохраняет инварианты и управляет порядком действий: применяет `RuleChain`, вызывает доменные сервисы, пишет бизнес‑лог, инициирует побочные эффекты (задачи/изменение тарифов/перерасчеты и т.п.).
4. **Доменные сервисы (агрегируемые в use-cases)** (`src/Modules/Common/Domain/Service`). Переиспользуемые доменные операции без привязки к конкретному HTTP‑сценарию: работа с тарифами/задачами/пользователями и т.д. Use-case, как правило, собирается из нескольких таких сервисов.
5. **Правила (Rules engine)** (`src/Modules/Common/Domain/Service/Rules`). `Rule` — атомарная проверка, возвращающая `RuleResult`; `RuleChain` — композиция правил с режимами `SOFT/HARD`, используемая use-case’ами для валидации бизнес‑условий до изменения данных.
6. **Инфраструктура** (`src/Modules/Common/Infrastructure/Service`). Кросс‑доменные вещи: логирование (`LoggerService`), авторизация/сессия (`Auth`, `UserSessionService`), стандартные исключения, интеграции и техническая обвязка.
7. **Сущности и репозитории** (`src/Modules/*/Entity`, `src/Modules/*/Repository`). Сущности и доступ к данным; репозитории инкапсулируют запросы к БД и не содержат orchestration‑логики.

### Common: доменный и инфраструктурный слои
- `src/Modules/Common/Domain` — чистый домен: сущности, репозитории, сервисы и правила. Здесь нет зависимостей на внешние каналы.
- `src/Modules/Common/Infrastructure` — обвязка вокруг домена. Она отвечает за кросс‑доменные задачи: логирование, авторизацию, работу с сессией, стандартизированные исключения (`Infrastructure/Exception`), вспомогательные трейты. Инфраструктура используется из любого модуля и отделена от бизнес‑кода.

### Глобальный обработчик ошибок и слушатели ответа
Каталог `src/Modules/UserCabinet/EventListener` содержит слушателей ядра HTTP‑стека:

- `ExceptionListener` (`src/Modules/UserCabinet/EventListener/ExceptionListener.php:13`) — единая точка обработки ошибок. Конвертирует доменные `BusinessException`, `ImportantBusinessException`, `AuthException`, `ValidationException` в консистентные JSON‑ответы, логирует критичные события и скрывает 500‑ки от клиента.
- `ResponseListener` (`src/Modules/UserCabinet/EventListener/ResponseListener.php:8`) — формирует стандартный JSON‑ответ после успешной обработки запроса. Слушатель убеждается, что модульные контроллеры всегда отдают JSON‑структуру одного вида.
- `AuthListener` (`src/Modules/UserCabinet/EventListener/AuthListener.php:11`) — подключается на уровне `KernelEvents::CONTROLLER` и решает, требуется ли авторизация для конкретного контроллера (см. раздел ниже).

### Авторизация контроллеров
Каждый контроллер модуля реализует метод `authenticate(): bool` (см. базовый `src/Modules/UserCabinet/Controllers/APIv1/Controller.php:32`).  
`AuthListener` проверяет этот метод перед выполнением экшена:

1. Контроллер помечает, нужна ли авторизация (например, `LoginController::authenticate()` возвращает `false`, а `UserProfileController::authenticate()` — `true`).
2. Если для маршрута требуется авторизация и пользователь не прошёл проверку `Auth::checkAuth()`, выбрасывается `AuthException`, дальше работает глобальный обработчик ошибок.

Такое решение позволяет гибко управлять доступом без копирования кода в каждом действии.

### Слушатели, формирующие ответ
Ответ фронту собирается централизованно:

- Контроллеры возвращают `JsonResponse` через хелперы базового контроллера.
- `ResponseListener` доводит успешные ответы до общего формата (`{'data': ..., 'message': ...}`).
- `ExceptionListener` формирует тело ответа при ошибках. Благодаря этому контракт API остаётся стабильным независимо от внутренних изменений.

### Дополнительные функциональные моменты
- **Движок бизнес‑правил** (`src/Modules/Common/Domain/Service/Rules`). Он позволяет собирать цепочки (`RuleChain`) из атомарных правил (`Rule`) и использовать их в use-case’ах и сервисах. Это обеспечивает повторное использование проверок и детальный аудит (см. `src/Modules/Common/Domain/Service/Rules/README.md`).
- **Бизнес‑/техническое логирование** (`Common/Infrastructure/Service/Logger`). Сервисы ядра автоматически прокидывают `BusinessLogDto`/`ErrorLogDto`, а `ExceptionListener` логирует критичные кейсы.
- **Сессии и контекст пользователя** (`Common/Infrastructure/Service/Auth`). `UserSessionService` предоставляет ID пользователя для доменных сервисов, `Auth` управляет состоянием авторизации.
- **Proxy/CI настройки** — в конце файла приведены команды для работы через корпоративный прокси; благодаря этому окружение разворачивается одинаково в локальной и CI‑среде.

Итоговая картина: модульные контроллеры и сервисы описывают процессы конкретного домена, ядро `Common` хранит общие инварианты и транзакции, инфраструктура берёт на себя пересекающиеся задачи (логирование, авторизацию, формат ответов), а глобальные слушатели обеспечивают единообразное поведение HTTP‑слоя.

# Функциональные тесты

## Где лежат и как устроены
Функциональные тесты находятся в `tests/Functional`.  
Для модуля личного кабинета структура:

```
tests/Functional
└─ UserCabinet
   └─ APIv1
      ├─ LoginControllerTest.php
      ├─ PaymentsControllerTest.php
      ├─ GetStatusFreezeTest.php
      └─ ...
```

Каждый endpoint — отдельный файл. Это упрощает поддержку и поиск проблем.

## Базовый механизм
1. Используется базовый класс `tests/Functional/TransactionalWebTestCase.php`.
2. В `setUp()` создаётся `KernelBrowser` и открывается транзакция.
3. `disableReboot()` закрепляет один контейнер и одно соединение на весь тест.
4. В `tearDown()` — `rollback` и закрытие EntityManager.

Это означает:
- все изменения в БД в рамках теста откатываются;
- тесты изолированы друг от друга;
- можно безопасно проверять создание/обновление сущностей.

## Как добавить новый тест
1. Создай файл `tests/Functional/<Module>/<Version>/<EndpointName>Test.php`.
2. Наследуйся от `TransactionalWebTestCase`.
3. Используй `$this->client` (не вызывай `createClient()` в тесте).
4. Если нужен логин — вызывай `$this->loginClient($this->client, $user)`.

Пример:
```php
class GetExampleTest extends TransactionalWebTestCase
{
    public function testGetExample(): void
    {
        $this->loginClient($this->client);
        $this->client->request('GET', '/user-cabinet/example');

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }
}
```

## Проверка результата
- Минимум: статус и `content-type`.
- Для JSON — валидировать структуру `data` и ключевые поля.
- Для бизнес‑действий — проверять следы в БД (например, созданную задачу).

## Подбор тестовых пользователей
По умолчанию берётся последний пользователь с `blockState=0` и `isJuridical=0`.  
Если нужен конкретный пользователь (например, frozen/blocked), ищем его внутри теста запросом в БД.

## Запуск
```
php bin/phpunit --testsuite "Project Test Suite"
```
```
php bin/phpunit --testdox
```

# Явно задать прокси для проблем с сестью
composer config -g --unset http-proxy 2>/dev/null || true
composer config -g --unset https-proxy 2>/dev/null || true

composer config -g http-proxy  http://proxy.izet.ru:3128
composer config -g https-proxy http://proxy.izet.ru:3128

env | grep -i proxy
curl -I https://repo.packagist.org/packages.json
composer diagnose
composer -vvv install
