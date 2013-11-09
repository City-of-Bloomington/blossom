<?php
/**
 * @copyright 2007-2012 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Zend\Db\Sql\Select $users
 */
use Application\Models\Person;
use Blossom\Classes\View;
?>
<div class="interfaceBox">
	<h2>User Accounts
		<?php
			if (Person::isAllowed('users')) {
				echo "<a class=\"add button\" href=\"".BASE_URI."/users/update\">Add Account</a>";
			}
		?>
	</h2>
	<table>
		<thead>
			<tr><th></th>
				<th>Username</th>
				<th>Name</th>
				<th>Authentication</th>
				<th>Role</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($this->users as $user) {
				$editButton = '';
				$deleteButton = '';
				if (Person::isAllowed('users')) {
					$editButton = "
					<a class=\"edit button\"
						href=\"".BASE_URI."/users/update?user_id={$user->getId()}\">
						Edit Account
					</a>
					";
					$deleteButton = "
					<a class=\"delete button\"
						href=\"".BASE_URI."/users/delete?user_id={$user->getId()}\">
						Delete Account
					</a>
					";
				}

				$name = View::escape($user->getFullname());
				echo "
				<tr><td>$editButton</td>
					<td>{$user->getUsername()}</td>
					<td>$name</td>
					<td>{$user->getAuthenticationMethod()}</td>
					<td>{$user->getRole()}</td>
					<td>$deleteButton</td>
				</tr>
				";
			}
		?>
		</tbody>
	</table>
</div>
