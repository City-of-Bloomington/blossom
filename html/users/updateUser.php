<?php
/**
 * @copyright 2006-2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET user_id
 */
verifyUser('Administrator');
if (isset($_GET['user_id'])) {
	$user = new User($_GET['user_id']);
}

if (isset($_POST['user_id'])) {
	$user = new User($_POST['user_id']);
	foreach ($_POST['user'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$user->$set($value);
	}

	$person = $user->getPerson();
	foreach($_POST['person'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$person->$set($value);
	}

	try {
		$person->save();
		$user->save();
		header('Location: '.BASE_URL.'/users');
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('users/updateUserForm.inc',array('user'=>$user));
echo $template->render();
