<?php
$_SERVER['SITE_HOME'] = __DIR__.'/data';
include realpath(__DIR__.'/../Web/bootstrap.php');

$GLOBALS['DI'       ] = $DI;
$GLOBALS['ROUTES'   ] = $ROUTES;
$GLOBALS['DATABASES'] = $DATABASES;
