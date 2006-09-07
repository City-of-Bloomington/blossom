<?php
/*
	$_POST variables:	user [ authenticationMethod		# Optional
								username				password
								roles					firstname
														lastname
														department
														phone
							]
*/
	verifyUser("Administrator");

	$view = new View();
	$view->addBlock("users/addUserForm.inc");
	if (isset($_POST['user']))
	{
		$user = new User();
		foreach($_POST['user'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$user->$set($value);
		}

		if ($user->getAuthenticationMethod() == "LDAP")
		{
			# Load the rest of their stuff from LDAP
			$ldap = new LDAPEntry($user->getUsername());
			$user->setFirstname($ldap->getFirstname());
			$user->setLastname($ldap->getLastname());
			$user->setDepartment($ldap->getDepartment());
			$user->setPhone($ldap->getPhone());
		}

		try
		{
			$user->save();
			Header("Location: home.php");
		}
		catch (Exception $e) { $_SESSION['errorMessages'][] = $e; }
	}

	$view->render();
?>