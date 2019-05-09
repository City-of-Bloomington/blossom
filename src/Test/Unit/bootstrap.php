<?php
/**
 * Where on the filesystem this application is installed
 */
define('APPLICATION_HOME', realpath(__DIR__.'/../../..'));
define('VERSION', trim(file_get_contents(APPLICATION_HOME.'/VERSION')));

define('SITE_HOME', __DIR__);
include SITE_HOME.'/test_config.php';

$loader = require APPLICATION_HOME.'/vendor/autoload.php';
include SITE_HOME.'/container.php';
include APPLICATION_HOME.'/routes.php';
include APPLICATION_HOME.'/access_control.php';
