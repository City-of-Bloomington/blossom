#!/bin/bash
# Creates a tarball containing a full snapshot of the data in the site
#
# @copyright Copyright 2011-2024 City of Bloomington, Indiana
# @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE
APPLICATION_NAME=blossom

BACKUP_DIR=/srv/backups/$APPLICATION_NAME
APPLICATION_HOME=/srv/sites/$APPLICATION_NAME
SITE_HOME=/srv/data/$APPLICATION_NAME
MYSQL_AUTH=/etc/mysql/debian.cnf
MYSQL_DBNAME=$APPLICATION_NAME

# How many days worth of tarballs to keep around
num_days_to_keep=5

#----------------------------------------------------------
# No Editing Required below this line
#----------------------------------------------------------
now=`date +%s`
today=`date +%F`

if [ ! -d $BACKUP_DIR ]
	then mkdir $BACKUP_DIR
fi
cd $BACKUP_DIR
mkdir $today

# Dump the database
mysqldump --defaults-extra-file=$MYSQL_AUTH $MYSQL_DBNAME > $today/$MYSQL_DBNAME.sql

# Copy any data directories into this directory, so they're backed up, too.
# For example, if we had a media directory....
#cp -R $APPLICATION_HOME/data/media $today/media

# Tarball the Data
tar czf $today.tar.gz $today
rm -Rf $today

# Purge any backup tarballs that are too old
for file in `ls`
do
	atime=`stat -c %Y $file`
	if [ $(( $now - $atime >= $num_days_to_keep*24*60*60 )) = 1 ]
	then
		rm $file
	fi
done
