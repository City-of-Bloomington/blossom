<?php
	verifyUser("Administrator");

	$view = new View();
	$view->addBlock("users/userList.inc");
	$view->render();
?>