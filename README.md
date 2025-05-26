## Деплой проекта

1. Настройка переменных среды **docker-compose**: 
    ```sh
    cp ./docker/.env.dist ./docker/.env
    ```
   - Укажите **NGINX_HOST_HTTP_PORT** внешний порт контейнера (по умолчанинию **80**)

2. Настройка переменных **Xdebug**:
    ```
    cp ./docker/php-fpm/.xdebug.ini.example ./docker/php-fpm/.xdebug.ini
    ```
   - Укажите **xdebug.idekey** - этот ключ указывается в ide при настройке xdebug
   - При необходимости измените **xdebug.client_port** для указания кастомного порта для xdebug

3. Настройка окружения **symfony**
    ```sh
    cp ./.env.example ./.env
    ```
4. Запуск docker-compose
    ```sh
    docker compose -f ./docker/docker-compose.yml up build
    docker compose -f ./docker/docker-compose.yml up
    ```
   либо 
   ```sh
   docker compose -f ./docker/docker-compose.yml up --build -d
   ```
5. Подтягивание зависимостей **composer**  
    На **develop**:
    ```sh
    composer install
    ```
    На **production**:
    ```sh
    composer install --no-dev --optimize-autoloader
    ```
### Особенности настройки

1. Настройки **Xdebug** в PHPstorm.
При указании сервера в ide (Settings -> Languages&Frameworks -> PHP -> Servers) название сервера необходимо давать такое же, какое указано в ./docker/docker-compose.yml в переменной PHP_IDE_CONFIG

2. Установка из контейнера

   ```sh
   docker compose -f ./docker/docker-compose.yml exec -u www-data php-fpm bash
   ```
   (https://symfony.com/doc/current/setup.html)  
   В контейнере запускаем:
  
   ```
   composer create-project symfony/skeleton:"7.2.x" my_project_directory
   ```
   
   Переносим все файлы из my_project_directory/ в корневую директорию.
   
### Особенности разработки

1. По ссылке /_profiler в *dev* среде мы можем отобразить **Symfony Profiler**

## Архитектурные особенности

### Структура папок

```text
src/
└── Modules
    ├── Common                          ── общие элементы, доступные всем модулям
    │   └── BaseRepository.php
    └── UserCabinet                     ── независимый модуль
        ├── Controllers                 ── HTTP‑контроллеры (версионируются)
        │   └── APIv1
        │       └── Controller.php
        ├── Entity                      ── Doctrine‑сущности
        │   └── User
        │       └── User.php
        ├── EventListener               ── слушатели событий Symfony
        │   └── ExceptionListener.php
        ├── Repository                  ── доступ к БД
        │   ├── Exception               ── исключения репозитория
        │   └── User
        │       └── UserRepository.php
        └── Service                     ── бизнес‑сервисы модуля
            ├── Exception               ── доменные / бизнес‑исключения
            │   ├── BusinessException.php
            │   └── UserNotFoundException.php
            └── UserInformation.php
```

**Модули**  
Каждый каталог внутри `Modules/` — автономная часть монолита со своими контроллерами, сущностями, сервисами и репозиториями. Модули развиваются независимо друг от друга.

---

### Слои приложения

| Слой | Ответственность | Расположение |
|------|-----------------|--------------|
| **ControllerLayer** | Маршруты, первичная валидация, вызов сервисов, формирование HTTP‑ответа | `Modules/<Module>/Controllers` |
| **DomainServiceLayer** | Бизнес‑действия, применение правил, выброс доменных исключений | `Modules/<Module>/Service` |
| **RepositoryLayer** | Специфичные запросы к БД, не покрываемые стандартным API Doctrine | `Modules/<Module>/Repository` |
| **EventListener** | Реакция на события Symfony (например, конвертация исключений в JSON) | `Modules/<Module>/EventListener` или `src/EventListener` |

---

### Детали каталогов

#### Controllers `Modules/UserCabinet/Controllers/APIv1`

* Версионирование через подкаталоги (`APIv1`, `APIv2`, …).
* Проверяют формат входных данных и вызывают сервис.
* Возвращают `JsonResponse` в нужном формате.

#### Services `Modules/UserCabinet/Service`

* Каждый публичный метод — одно бизнес‑действие.
* При нарушении правил бросают наследника `BusinessException`.

#### Entity `Modules/UserCabinet/Entity`

* Сущности описываются атрибутами Doctrine.
* Для крупных областей сущности группируются подпапками (например, `User`, `Tariff`).

#### Repository `Modules/UserCabinet/Repository`

* Наследуются от `BaseRepository` из `Modules/Common`.
* Содержат сложные выборки, вызовы процедур и т. п.
* Исключения репозитория лежат в `Repository/Exception`.

#### EventListener `Modules/UserCabinet/EventListener`

* Например, `ExceptionListener` преобразует доменные исключения в HTTP 4xx/5xx с JSON‑телом.
* Регистрируются автоматически через атрибут `#[AsEventListener]` или тег `kernel.event_listener`.

#### Exception

* Группируются по слою:
    * `Service/Exception/*` — бизнес‑исключения.
    * `Repository/Exception/*` — ошибки БД.
    * При необходимости могут быть и контроллерные исключения.

---

### Практика распределения ответственности

* **Формат/структура входных данных** проверяется в контроллере (или через DTO).
* **Бизнес‑валидность** (существует ли пользователь, разрешено ли действие) проверяет сервис.
* **Транспортный слой** (контроллер + listener) превращает исключения из сервиса в единообразные JSON‑ответы.