<?php
/**
 * @copyright 2009-2011 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Person $this->person
 */
$username = $this->person->getUsername();
if (!$username && userIsAllowed('Users')) {
	$username = $this->template->linkButton(
		'Create Account',BASE_URL.'/users/updateUser.php?person_id='.$this->person->getId(),'add'
	);
}

$name = $this->escape($this->person->getFullname());
echo "
<h1>$name</h1>
<table>
	<tr><th>Username</th>
		<td>$username</td>
	</tr>
	<tr><th>Email</th>
		<td>{$this->person->getEmail()}</td>
	</tr>
</table>
";
