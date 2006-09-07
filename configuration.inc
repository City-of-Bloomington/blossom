<?php
/**
* @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
* This file is part of the City of Bloomington's web application Scaffolding.
* This Scaffolding is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* This Scaffolding is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this scaffolding; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
*/
/*
	Global settings
*/
	define("APPLICATION_NAME","application_name");	# Used to keep sessions on the same webserver seperate;

	define("GLOBAL_INCLUDES","/var/www/libraries/php");	# Where on the filesystem this package of libraries is installed.
														# These are shared among other applications we write, so they
														# are a seperate package we will release.

	define("APPLICATION_HOME","/var/www/sites/application_name");	# Where on the filesystem this is installed
	define("BASE_URL","/application_name");							# The base URL to get to this site on this webserver

	#--------------------------------------------------------------------------
	# Database Setup
	#--------------------------------------------------------------------------
	try { $PDO = new PDO("mysql:unix_socket=/tmp/mysql.sock;dbname=application_name","username","password",array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION)); }
	catch (PDOException $e) { die($e->getMessage()); }

	#--------------------------------------------------------------------------
	# LDAP Configuration
	# This is required in order to use the LDAP authentication
	# If you do not want to use LDAP authentication, you can comment this out
	#--------------------------------------------------------------------------
	define("LDAP_DOMAIN","city.bloomington.in.us");
	define("LDAP_DN","ou=people,o=".LDAP_DOMAIN);
	define("LDAP_USERNAME_ATTRIBUTE","uid");
	define("LDAP_ADMIN_USER","username");
	define("LDAP_ADMIN_PASS","password");
	define("LDAP_SERVER","ldap.bloomington.in.gov");
	define("LDAP_PASSWORD_ATTRIBUTE","userpassword");


	# Import global functions that we use for many applications we write
	include(GLOBAL_INCLUDES."/ITSFunctions.inc");

	#--------------------------------------------------------------------------
	# Session Startup
	#--------------------------------------------------------------------------
	session_start();
?>