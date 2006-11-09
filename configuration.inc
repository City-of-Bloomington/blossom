<?php
/**
 * @package GlobalSettings
 * @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 */
	/**
	 * Used to keep sessions on the same webserver seperate;
	 */
	define("APPLICATION_NAME","application_name");

	/**
	 * Where on the filesystem the framework is installed.
	 * These are shared among other applications we write, so they
	 * are a seperate package we will release.
	 */
	define("FRAMEWORK","/var/www/libraries/framework");


	/**
	 * Where on the filesystem this application is installed
	 * and the URL to get to this site on this webserver.
	 * The URL is only used for internal linking and does not need
	 * the http://some.server.com part.
	 */
	define("APPLICATION_HOME","/var/www/sites/application_name");
	define("BASE_URL","/application_name");

	/**
	 * Used when there's an error on the site.  The Framework will
	 * print out a nice error message, encouraging users to report any problems
	 * See: FRAMEWORK/ITSFunctions.inc
	 */
	define("ADMINISTRATOR_NAME","Mr. Site Admin");
	define("ADMINISTRATOR_EMAIL","admin@servername.com");


	/**
	 * Database Setup
	 */
	try
	{
		$PDO = new PDO("mysql:unix_socket=/tmp/mysql.sock;dbname=application_name",
							"username","password",
							array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
	}
	catch (PDOException $e) { die($e->getMessage()); }

	/**
	 * LDAP Configuration
	 * This is required in order to use the LDAP authentication
	 * If you do not want to use LDAP authentication, you can comment this out
	 */
	define("LDAP_DOMAIN","city.bloomington.in.us");
	define("LDAP_DN","ou=people,o=".LDAP_DOMAIN);
	define("LDAP_USERNAME_ATTRIBUTE","uid");
	define("LDAP_ADMIN_USER","username");
	define("LDAP_ADMIN_PASS","password");
	define("LDAP_SERVER","ldap.bloomington.in.gov");
	define("LDAP_PASSWORD_ATTRIBUTE","userpassword");


	/**
	 * Import global functions that we use for many applications we write
	 */
	include(FRAMEWORK."/ITSFunctions.inc");

	/**
	 * Session Startup
	 */
	session_start();
?>