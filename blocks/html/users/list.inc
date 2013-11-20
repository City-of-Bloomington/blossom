<?php
/**
 * @copyright 2007-2013 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Zend\Db\Sql\Select $users
 */
use Application\Models\Person;
use Blossom\Classes\View;
?>
<div class="interfaceBox">
	<h2><?php
			echo $this->_(['labels.user','labels.users',2]);

			$button = '<a class="%s button" href="%s">%s</a>';
			if (Person::isAllowed('users')) {
				echo sprintf($button,'add',BASE_URI.'/users/update',$this->_('labels.create_account'));
			}
		?>
	</h2>
	<table>
		<thead>
			<tr><th></th>
				<th><?php echo $this->_('labels.username'); ?></th>
				<th><?php echo $this->_('labels.name'); ?></th>
				<th><?php echo $this->_('labels.authenticationMethod'); ?></th>
				<th><?php echo $this->_('labels.role'); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($this->users as $user) {
				$editButton = '';
				$deleteButton = '';
				if (Person::isAllowed('users')) {
					$editButton = sprintf($button,
						'edit',
						BASE_URI.'/users/update?user_id='.$user->getId(),
						$this->_('labels.edit_account')
					);
					$deleteButton = sprintf($button,
						'delete',
						BASE_URI.'/users/delete?user_id='.$user->getId(),
						$this->_('labels.delete_account')
					);
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
