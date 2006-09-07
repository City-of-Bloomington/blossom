<?php
/*
	$_GET variables:	id
	---------------------------------------------------------------------------
	$_POST variables:	id
						user [ authenticationMethod		# Optional
								username				password
								roles					firstname
														lastname
														department
														phone
							]
*/
	verifyUser("Administrator");

	$view = new View();
	if (isset($_GET['id'])) { $view->user = new User($_GET['user']); }

	$view->addBlock("users/updateUserForm.inc");
	if (isset($_POST['user']))
	{
		$user = new User($_POST['id']);
		foreach($_POST['user'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$user->$set($value);
		}

		$view->user = $user;
		try
		{
			$user->save();
			Header("Location: home.php");
		}
		catch (Exception $e) { $_SESSION['errorMessages'][] = $e; }
	}

	$view->render();
?>