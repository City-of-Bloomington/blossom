<?php
/**
 * @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 */
/*
	$_GET variables:	id
	---------------------------------------------------------------------------
	$_POST variables:	id
						user
*/
	verifyUser("Administrator");
	if (isset($_GET['id'])) { $user = new User($_GET['id']); }
	if (isset($_POST['id']))
	{
		$user = new User($_POST['id']);
		foreach($_POST['user'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$user->$set($value);
		}

		try
		{
			$user->save();
			Header("Location: home.php");
			exit();
		}
		catch (Exception $e) { $_SESSION['errorMessages'][] = $e; }
	}

	$template = new Template();
	$template->blocks[] = new Block("users/updateUserForm.inc",array('user'=>$user));
	$template->render();
?>