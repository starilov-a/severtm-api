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
     docker compose --env-file ./docker/.env -f ./docker/docker-compose.yml up -d --build
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
`Модуль (например, UserCabinet)` → `Основные бизнес‑логики ядра (Common\Domain\Service)` → `Поддерживающие сервисы и инфраструктура (логирование, правила, задачи)` → `Сущности и репозитории`.

1. **Модульный слой** (`Modules/UserCabinet/Controllers|Service`). Контроллеры принимают HTTP‑запросы, подготавливают DTO и вызывают модульные сервисы.
2. **Сервисы ядра** (`Modules/Common/Domain/Service`). Именно здесь стартуют транзакции, прикручиваются `RuleChain`, формируются доменные события и гарантируется консистентность.
3. **Поддерживающие сервисы** (`Modules/Common/Infrastructure/Service`, вспомогательные сервисы модуля). Они не содержат бизнес‑решений, но помогают выдерживать инварианты: логирование (`LoggerService`), авторизация (`Auth`, `UserSessionService`), генерация задач, уведомления.
4. **Сущности и репозитории** (`Modules/*/Entity`, `Modules/*/Repository`). Вся работа с БД завершается на этом уровне; репозитории наследуются от общего `BaseRepository`.

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
- **Движок бизнес‑правил** (`src/Modules/Common/Domain/Service/Rules`). Он позволяет собирать цепочки (`RuleChain`) из атомарных правил (`Rule`) и использовать их в сервисах ядра. Это обеспечивает повторное использование проверок и детальный аудит (см. `src/Modules/Common/Domain/Service/Rules/README.md`).
- **Бизнес‑/техническое логирование** (`Common/Infrastructure/Service/Logger`). Сервисы ядра автоматически прокидывают `BusinessLogDto`/`ErrorLogDto`, а `ExceptionListener` логирует критичные кейсы.
- **Сессии и контекст пользователя** (`Common/Infrastructure/Service/Auth`). `UserSessionService` предоставляет ID пользователя для доменных сервисов, `Auth` управляет состоянием авторизации.
- **Proxy/CI настройки** — в конце файла приведены команды для работы через корпоративный прокси; благодаря этому окружение разворачивается одинаково в локальной и CI‑среде.

Итоговая картина: модульные контроллеры и сервисы описывают процессы конкретного домена, ядро `Common` хранит общие инварианты и транзакции, инфраструктура берёт на себя пересекающиеся задачи (логирование, авторизацию, формат ответов), а глобальные слушатели обеспечивают единообразное поведение HTTP‑слоя.

# Явно задать прокси для проблем с сестью
composer config -g --unset http-proxy 2>/dev/null || true
composer config -g --unset https-proxy 2>/dev/null || true

composer config -g http-proxy  http://proxy.izet.ru:3128
composer config -g https-proxy http://proxy.izet.ru:3128

env | grep -i proxy
curl -I https://repo.packagist.org/packages.json
composer diagnose
composer -vvv install
