SHELL=/bin/bash

PLATFORM := $(shell uname -s)

ifeq ($(COMPOSER_HOME),)
export COMPOSER_HOME=~/.composer
endif

export HOST_UID := $(shell id -u)
export HOST_GID := $(shell id -g)

COMPOSER_RUN := docker run --rm --volume `pwd`:/app:cached --user ${HOST_UID}:${HOST_GID} composer:latest

DOCKER_COMPOSE := docker-compose -f docker-compose.yml
DOCKER_COMPOSE_TEST := docker-compose -f docker-compose.test.yml

~/.composer:
	mkdir -p ~/.composer

## vendor: Install app dependencies
vendor: ~/.composer composer.json composer.lock
	 ${COMPOSER_RUN} install

## composer: Entrypoint for running Composer (use bin/composer)
.PHONY: composer
composer:
	@${COMPOSER_RUN} $(ARGS) --ansi

## build: Build the Docker images
.PHONY: build
build: 
	${DOCKER_COMPOSE} build --force-rm --pull

## up: Start all services for this project
.PHONY: up
up: vendor
	${DOCKER_COMPOSE} up -d --remove-orphans
	@echo "#########################################################"
	@echo ""
	@echo "Done, now open http://oat-x.docker:9001 in your browser"
	@echo ""
	@echo "#########################################################"

## restart: Helps to 
restart: vendor
	${DOCKER_COMPOSE} stop
	${DOCKER_COMPOSE} up -d

## down: Stop and remove all containers and volumes for this project
.PHONY: down
down:
	${DOCKER_COMPOSE} down --remove-orphans -v

## test: Start all services and run the tests
.PHONY: test
test:
	${DOCKER_COMPOSE} run --rm backend php vendor/bin/phpunit

## ps: Show the status of the containers
.PHONY: ps
ps:
	${DOCKER_COMPOSE} ps

## logs: Show and follow the container logs
.PHONY: logs
logs:
	${DOCKER_COMPOSE} logs -f

## destroy: Remove everything to be able to start all over
.PHONY: destroy
destroy:
	rm -rvf vendor/*
	rm -rvf var/*
	${DOCKER_COMPOSE} down -v --remove-orphans --rmi all
