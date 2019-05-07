SASS := $(shell command -v sassc 2> /dev/null)
MSGFMT := $(shell command -v msgfmt 2> /dev/null)
LANGUAGES := $(wildcard language/*/LC_MESSAGES)

VERSION := $(shell cat VERSION | tr -d "[:space:]")
COMMIT := $(shell git rev-parse --short HEAD)

APPLICATION_NAME := blossom

default: clean compile package

deps:
ifndef SASS
	$(error "sassc is not installed")
endif
ifndef MSGFMT
	$(error "gettext is not installed")
endif

clean:
	rm -Rf build
	mkdir build

compile: deps $(LANGUAGES)
	cd                 public/css && sassc -mt compact screen.scss screen-${VERSION}.css
	cd data/Themes/COB/public/css && sassc -mt compact screen.scss screen-${VERSION}.css

package:
	rsync -rl --exclude-from=buildignore --delete . build/${APPLICATION_NAME}
	cd build && tar czf ${APPLICATION_NAME}.tar.gz ${APPLICATION_NAME}

$(LANGUAGES): deps
	cd $@ && msgfmt -cv *.po
