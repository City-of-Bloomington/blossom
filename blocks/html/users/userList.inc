<?php
/**
 * @copyright 2007-2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param UserList $this->userList
 */
$addButton = '';
if (userHasRole('Administrator')) {
	$addButton = '<a class="add button" href="'.BASE_URL.'/users/addUser.php">Add</a>';
}
?>
<div class="interfaceBox">
	<h1><?php echo $addButton; ?>Users</h1>
	<table>
	<?php
		foreach ($this->userList as $user) {
			$editButton = '';
			if (userHasRole('Administrator')) {
				$url = new URL(BASE_URL.'/users/updateUser.php');
				$url->user_id = $user->getId();
				$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
			}

			echo "
			<tr><td>$editButton</td>
				<td>{$user->getUsername()}</td>
				<td>{$user->getFirstname()} {$user->getLastname()}</td>
				<td>{$user->getAuthenticationMethod()}</td>
				<td>
			";
					foreach ($user->getRoles() as $role) {
						echo "$role ";
					}
			echo "
				</td>
			</tr>
			";
		}
	?>
	</table>
</div>
