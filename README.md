## Задание
Тестовое задание на позицию backend-разработчик PHP

Требуется реализовать систему прав пользователя со следующим функционалом:

- есть, как минимум, 3 права: «send_messages», «service_api», «debug»;
- есть группы пользователей, которые включают различные сочетания прав;
- пользователь может входить в разные группы, при этом права объединяются;
- пользователь может также входить в группу временно заблокированных, в которой одно или несколько прав отменяются, хотя он продолжает быть членом других групп.
- 
Есть 3 пользователя с userId 1, 2 и 3.

Нужен API, реализующий добавление и удаление пользователя из группы, а также показывающий список группы и конечный набор прав для конкретного пользователя. Если право явно не задано, оно должно отображаться как «нет» или «false».

Добавить тесты на PHPUnit.

Использовать фреймворки нельзя, библиотеки можно. Формат API – JSON. СУБД MySQL. PHP 5.6 и выше. Написать инструкцию по запуску сервера и примеры использования API.

Результат выложить в виде публичного репозитория на GitHub и предоставить ссылку на него.

## Создание файла .env
```shell
cp .env.example .env
```

Пример заполнения `.env` файла

```dotenv
######### DOCKER ##########
PHP_CONTAINER_NAME=cnsloyalty_php
NGINX_CONTAINER_NAME=cnsloyalty_nginx
DB_CONTAINER_NAME=cnsloyalty_db
DB_TEST_CONTAINER_NAME=cnsloyalty_db_test
###########################

APP_ENV=development

########### MYSQL ############
# MYSQL_VERSION=8.0.21
MYSQL_PORT=3306
MYSQL_EXPOSE_PORT=3306
MYSQL_HOST=db
MYSQL_DATABASE=cnsloyalty
MYSQL_ROOT_USER=root
MYSQL_ROOT_PASSWORD=e94AoWMywhgZ
MYSQL_USER=user
MYSQL_PASSWORD=BbdgJLNNK21c
##############################

########### MYSQL TEST ###########
# MYSQL_VERSION=8.0.21
MYSQL_TEST_PORT=3306
MYSQL_TEST_EXPOSE_PORT=3307
MYSQL_TEST_HOST=db_test
MYSQL_TEST_DATABASE=cnsloyalty
MYSQL_TEST_ROOT_USER=root
MYSQL_TEST_ROOT_PASSWORD=ovtrn93RsBKe
MYSQL_TEST_USER=user
MYSQL_TEST_PASSWORD=5KkK1pyVfU5V
##################################

########### MYADMIN ############
MYADMIN_PORT=80
MYADMIN_EXPOSE_PORT=8080
##############################

########## NGINX ############
NGINX_PORT=80
NGINX_EXPOSE_PORT=80
#############################
```

## Сборка контейнеров
```shell
docker compose build
```

## Поднятие проекта
```shell
docker compose up -d
```

Необходимо подождать пока контейнер `composer` установит все зависимости

Чтобы узнать, закончил ли он работу, можно выполнить

```shell
docker compose logs composer -f
```

## Тесты

Для начала изменить значение переменной окружения `APP_ENV` в файле `.env` в `testing`

Войти внутрь контейнера php

```shell
docker compose exec php bash
```

Перейти в директорию app

```shell
cd app/
```

Запустить тесты

```shell
./vendor/bin/phpunit
```