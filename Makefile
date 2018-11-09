INV=\033[7m
NC=\033[0m

.PHONY: all clean build test
$(V).SILENT:

all: clean build test
	which composer > /dev/null

clean:
	echo -e '\n${INV} ###  CLEAN  ### ${NC}\n'
	rm -rfv ./build

build:
	echo -e '\n${INV} ###  BUILD  ### ${NC}\n'
	composer -n install

test:
	echo -e '\n${INV} ###  TESTS  ### ${NC}\n'
	./vendor/bin/phpunit --bootstrap vendor/autoload.php src/test/php
