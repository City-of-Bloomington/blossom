<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 *
 */
include '../configuration.inc';

// Make sure we don't already have data in the system
$zend_db = Database::getConnection();
$count = $zend_db->fetchOne('select count(*) as count from people');
echo "Found $count people already in the system\n";
if ($count == 0) {

	$person = explode(' ',ADMINISTRATOR_NAME);
	$firstname = $person[0];
	$lastname = isset($person[1]) ? $person[1] : '';

	$zend_db->insert('people',array('firstname'=>$firstname,
									'lastname'=>$lastname,
									'email'=>ADMINISTRATOR_EMAIL));
	$person_id = $zend_db->lastInsertID('people','id');
	echo "Inserted person $person_id\n";

	$password = md5('admin');
	$zend_db->insert('users',array('person_id'=>$person_id,
								   'username'=>'admin',
								   'password'=>$password,
								   'authenticationmethod'=>'local'));
	$user_id = $zend_db->lastInsertID('users','id');
	echo "Inserted user $user_id\n";

	$zend_db->insert('roles',array('name'=>'Administrator'));
	$role_id = $zend_db->lastInsertID('roles','id');
	echo "Inserted role $role_id\n";

	$zend_db->insert('user_roles',array('user_id'=>$user_id,'role_id'=>$role_id));
	echo "User $user_id now has role $role_id\n";
}
