<?php
/**
 * @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 */
/*
	$_POST variables:	user
*/
	verifyUser("Administrator");
	if (isset($_POST['user']))
	{
		$user = new User();
		foreach($_POST['user'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$user->$set($value);
		}

		# Load their information from LDAP
		# Delete this statement if you're not using LDAP
		if ($user->getAuthenticationMethod() == "LDAP")
		{
			$ldap = new LDAPEntry($user->getUsername());
			$user->setFirstname($ldap->getFirstname());
			$user->setLastname($ldap->getLastname());
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
	$template->blocks[] = new Block("users/addUserForm.inc");
	$template->render();
?>