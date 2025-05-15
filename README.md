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