<?php
/**
 * @copyright 2007-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->users
 */
?>
<section>
    <header>
        <h1><?= $this->_(['user', 'users', count($this->users)]); ?></h1>
        <div class="tools">
        <?php
            $helper = $this->template->getHelper('buttonLink');
            $userCanEdit   = parent::isAllowed('users', 'update');
            $userCanDelete = parent::isAllowed('users', 'delete');
            if ($userCanEdit) {
                echo $helper->buttonLink(
                    self::generateUri('users.update'),
                    $this->_('user_add'),
                    'add'
                );
            }
        ?>
        </div>
    </header>

	<table>
		<thead>
			<tr><th></th>
				<th><?= $this->_('username'); ?></th>
				<th><?= $this->_('name'); ?></th>
				<th><?= $this->_('authentication_method'); ?></th>
				<th><?= $this->_('role'); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($this->users as $user) {
				$editButton   = '';
				$deleteButton = '';
				if ($userCanEdit) {
					$editButton = $helper->buttonLink(
                        self::generateUri('users.update', ['id'=>$user->id]),
						$this->_('user_edit'),
						'edit'
					);
                }
                if ($userCanDelete) {
					$deleteButton = $helper->buttonLink(
                        self::generateUri('users.delete', ['id'=>$user->id]),
						$this->_('user_delete'),
						'delete'
					);
				}

				$username = parent::escape($user->username);
				$name     = parent::escape("{$user->firstname} {$user->lastname}");
				$role     = parent::escape($user->role);
				$auth     = parent::escape($user->authentication_method);
				echo "
				<tr><td>$editButton</td>
					<td>{$user->username}</td>
					<td>$name</td>
					<td>$auth</td>
					<td>$role</td>
					<td>$deleteButton</td>
				</tr>
				";
			}
		?>
		</tbody>
	</table>
</section>
