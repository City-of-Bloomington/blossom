#!/bin/bash
PHPCS=/usr/local/PHP_CodeSniffer/scripts/phpcs
STANDARD=COB

for FILE in `find ../ -name '*.php'`;
	do $PHPCS --standard=$STANDARD $FILE;
done
