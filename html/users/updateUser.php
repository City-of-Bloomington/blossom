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

	if (isset($_POST['user']))
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
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;

			$view = new View();
			$view->user = $user;
			$view->addBlock("users/updateUserForm.inc");
			$view->render();
		}
	}
	else
	{
		$view = new View();
		$view->user = new User($_GET['id']);
		$view->addBlock("users/updateUserForm.inc");
		$view->render();
	}
?>