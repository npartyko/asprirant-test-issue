Тестовое задание для PHP-программиста
=====================================

Installation
--------------

Clone repository

```
git clone git@github.com:npartyko/asprirant-test-issue.git
```

if docker
run container 

    docker-compose up

enter the container

    docker-compose exec app sh
    
install dependencies

    composer install
    
create schema db

     bin/console orm:schema-tool:create
     
get data from itunes

    bin/console fetch:trailers 

start php server

    php -S 0.0.0.0:8080 -t public


TODO
--------------
    
* Unit tests
* Refactor router
* Refactor middleware