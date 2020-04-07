<?php
/**
 * @copyright 2007-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->users
 */
declare (strict_types=1);
use Web\Block;
?>
<section>
    <header>
    <?php
        $actions = [];
        if (parent::isAllowed('users', 'update')) {
            $actions['user_add'] = [
                'uri'   => parent::generateUri("users.update"),
                'class' => 'add'
            ];
        }
        if ($actions) {
            $block = new Block('toolbox.inc', ['actions' => $actions]);
            echo $block->render('html', $this->template);
        }
    ?>
    </header>

	<table>
		<thead>
			<tr><th><?= $this->_('username'); ?></th>
				<th><?= $this->_('name'); ?></th>
				<th><?= $this->_('authentication_method'); ?></th>
				<th><?= $this->_('role'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($this->users as $user) {
				$username = parent::escape($user->username);
				$name     = parent::escape("{$user->firstname} {$user->lastname}");
				$role     = parent::escape($user->role);
				$auth     = parent::escape($user->authentication_method);
				$uri      = parent::generateUri('users.view', ['id'=>$user->id]);
				echo "
				<tr><td><a href=\"$uri\">{$user->username}</a></td>
					<td><a href=\"$uri\">$name</a></td>
					<td><a href=\"$uri\">$auth</a></td>
					<td><a href=\"$uri\">$role</a></td>
				</tr>
				";
			}
		?>
		</tbody>
	</table>
</section>
