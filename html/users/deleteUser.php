<?php
/*
	$_GET variables:	id
*/
	verifyUser("Administrator");

	$user = new User($_GET['id']);
	$user->delete();

	Header("Location: home.php");
?>