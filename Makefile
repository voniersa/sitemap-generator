# Makefile

PHP_BIN=php
COMPOSER=./composer.phar
PHPUNIT=vendor/bin/phpunit
TEST-CLI=docker-compose run --rm -e HOST_USER_ID=$(HOST_USER_ID) -w /var/www/code smc-php-cli /bin/sh -c

.PHONY: help
help:  ##display this help
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n"} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)

export HOST_USER_ID=$(shell id -u)

.PHONY: build_cli
build_cli: ##build the cli container
	docker-compose build smc-php-cli

##@ Composer

.PHONY: composer_install
composer_install: build_cli ##install composer dependencies
	${TEST-CLI} "cd /var/www/code && $(PHP_BIN) $(COMPOSER) install"

.PHONY: composer_update
composer_update: ##update composer dependencies
	docker-compose run --rm --no-deps -e HOST_USER_ID=$(HOST_USER_ID) smc-php-cli /bin/sh -c "cd /var/www/code && $(PHP_BIN) $(COMPOSER) update $(MODULE)"

.PHONY: composer_require
composer_require: ##require composer dependencies
	docker-compose run --rm --no-deps -e HOST_USER_ID=$(HOST_USER_ID) smc-php-cli /bin/sh -c "cd /var/www/code && $(PHP_BIN) $(COMPOSER) require $(MODULE)"

.PHONY: composer_autoload
composer_autoload: build_cli ##composer autoload will be build
	docker-compose run --rm -e HOST_USER_ID=$(HOST_USER_ID) smc-php-cli /bin/sh -c "cd /var/www/code && $(PHP_BIN) $(COMPOSER) dumpautoload"

##@ Execute

.PHONY: run
run: composer_install ##execute generator
	@echo "+++++ Sitemap generator started +++++"
	docker-compose run --rm --no-deps smc-php-cli php bin/sitemap-generator.php --base-url $(BASE_URL) --output-dir /var/www/code/
	@echo "+++++ Sitemap file was successfully generated +++++"
	docker-compose down -v

##@ Testing

.PHONY: test
test: sniff test_unit ##run code-sniffer and all tests

.PHONY: test_unit
test_unit: composer_install ##run unit tests
	@echo "+++++ Unit Tests +++++"
	$(TEST-CLI) "$(PHP_BIN) $(PHPUNIT) -c phpunit.xml --testsuite unit --coverage-html reports/html --coverage-clover reports/phpunit.coverage.xml"
	docker-compose down -v

.PHONY: sniff
sniff: composer_install ##run code sniffer
	@echo "+++++ SNIFFER +++++"
	docker-compose run --rm --no-deps smc-php-cli php vendor/bin/phpcs -w -p -s --standard=codingRuleset.xml bin/ src/ tests/
	docker-compose down -v

##@ Helper

.PHONY: sniffer_fix
sniffer_fix: composer_install ##automatic fixing of some code style errors
	@echo "+++++ SNIFFER FIXER +++++"
	docker-compose run --rm --no-deps smc-php-cli php vendor/bin/phpcbf --standard=codingRuleset.xml bin/ src/ tests/
	docker-compose down -v
