<?php
/**
 * @copyright Copyright (C) 2006-2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
verifyUser('Administrator');
if (isset($_POST['user'])) {
	$user = new User();
	foreach($_POST['user'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$user->$set($value);
	}

	// Load their information from LDAP
	// Delete this statement if you're not using LDAP
	if ($user->getAuthenticationMethod() == 'LDAP') {
		$ldap = new LDAPEntry($user->getUsername());
		$user->setFirstname($ldap->getFirstname());
		$user->setLastname($ldap->getLastname());
	}

	try {
		$user->save();
		header('Location: '.BASE_URL.'/users');
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('users/addUserForm.inc');
echo $template->render();
