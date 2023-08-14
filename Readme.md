## Installation

1. Клонируйте это репозиторий
2. Создайте файл `./.docker/.env.nginx.local`, используя `./.docker/.env.nginx` в качестве шаблона. Значение переменной NGINX_BACKEND_DOMAIN используется server_name в NGINX (по умолчанию `localhost`).
3. Перейти в папку `./docker` и выполнить `docker compose up -d`
4. Внутри php контейнера выполнить `composer install && vendor/bin/bdi detect drivers`
5. Выполнить миграции `php bin/console doctrine:migrations:migrate`
6. Тесы `php bin/console phpunit`


Ссылка для теста на товар из магазина  `https://rozetka.com.ua/361128564/p361128564/`
