<?php
/**
 * @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 */
	verifyUser("Administrator");
	$template = new Template();

	$userList = new UserList();
	$userList->find();
	$template->blocks[] = new Block("users/userList.inc",array("userList"=>$userList));

	$template->render();
?>