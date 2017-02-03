#!/bin/bash
APPNAME=blossom
DIR=`pwd`
BUILD=$DIR/build

declare -a dependencies=(msgfmt node-sass node npm)
for i in "${dependencies[@]}"; do
    command -v $i > /dev/null 2>&1 || { echo "$i not installed" >&2; exit 1; }
done


if [ ! -d $BUILD ]
	then mkdir $BUILD
fi

# Compile the Lanague files
cd $DIR/language
./build_lang.sh
cd $DIR

# Compile the SASS
cd $DIR/public/css
./build_css.sh
cd $DIR

# The PHP code does not need to actually build anything.
# Just copy all the files into the build
rsync -rlv --exclude-from=$DIR/buildignore --delete $DIR/ $BUILD/$APPNAME
cd $BUILD
tar czvf $APPNAME.tar.gz $APPNAME
