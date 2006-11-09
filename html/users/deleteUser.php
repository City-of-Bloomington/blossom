<?php
/**
 * @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 */
/*
	$_GET variables:	id
*/
	verifyUser("Administrator");

	$user = new User($_GET['id']);
	$user->delete();

	Header("Location: home.php");
?>