#.RECIPEPREFIX +=
#.DEFAULT_GOAL := help
#PROJECT_NAME=jump
#include .env

help:
	@echo "Welcome to $(PROJECT_NAME) IT Support, have you tried turning it off and on again?"

install:
	@composer install

test:
	docker exec crm_php php artisan test

migrate:
	docker exec crm_php php artisan migrate

analyse:
	./vendor/bin/phpstan analyse

generate:
	@docker exec crm_php php artisan ide-helper:models --write

nginx:
	@docker exec -it $(PROJECT_NAME)_nginx /bin/sh

php:
	@docker exec -it $(PROJECT_NAME)_php /bin/sh

mysql:
	@docker exec -it $(PROJECT_NAME)_mysql /bin/sh

redis:
	@docker exec -it $(PROJECT_NAME)_redis /bin/sh
