INV=\033[7m
NC=\033[0m

.PHONY: all clean lint lint-fix build test

all: clean style build test
	@which composer > /dev/null

clean:
	@echo -e '\n${INV} ###   CLEAN   ### ${NC}\n'
	rm -rfv ./build vendor/modules.php vendor/routes.php

style:
	@echo -e '\n${INV} ###   STYLE   ### ${NC}\n'
	./vendor/bin/php-cs-fixer fix . --rules=@PSR2 --dry-run

fix-style:
	@echo -e '\n${INV} ### FIX STYLE ### ${NC}\n'
	./vendor/bin/php-cs-fixer fix . --rules=@PSR2

build:
	@echo -e '\n${INV} ###   BUILD   ### ${NC}\n'
	composer -n install

test:
	@echo -e '\n${INV} ###   TESTS   ### ${NC}\n'
	./vendor/bin/phpunit --bootstrap vendor/autoload.php src/test/php
