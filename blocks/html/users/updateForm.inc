<?php
/**
 * @copyright 2006-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param User   $this->user
 * @param string $this->title
 */
$fields = [
    'id', 'authenticationMethod', 'role', 'username',
    'firstname', 'lastname', 'email'
];
foreach ($fields as $f) {
    $$f = parent::escape($this->user->$f);
}
?>
<section>
    <header>
        <h1><?= $this->title; ?></h1>
    </header>

	<form method="post" action="<?= self::generateUri('users.update'); ?>">
		<fieldset><legend><?= $this->_('user_info'); ?></legend>
			<input name="id" type="hidden" value="<?= $id; ?>" />
			<?php
                $field = $this->template->getHelper('field');

                global $AUTHENTICATION_METHODS;
                $options = [];
                foreach (array_keys($AUTHENTICATION_METHODS) as $m) { $options[] = ['value'=>$m]; }
                echo $field->field([
                    'name'    => 'authenticationMethod',
                    'id'      => 'authenticationMethod',
                    'label'   => $this->_('authenticationMethod'),
                    'value'   => $authenticationMethod,
                    'type'    => 'select',
                    'options' => $options
                ]);

                echo $field->field(['name'=>'username', 'id'=>'username', 'label'=>$this->_('username'), 'value'=>$username, 'required'=>true]);
                echo $field->field(['name'=>'password', 'id'=>'password', 'label'=>$this->_('password'), 'type'=>'password']);

				$options = [];
                global $ZEND_ACL;
                foreach (array_reverse($ZEND_ACL->getRoles()) as $r) { $options[] = ['value'=>$r]; }
				echo $field->field([
                    'name'    => 'role',
                    'id'      => 'role',
                    'label'   => $this->_('role'),
                    'value'   => $role,
                    'type'    => 'select',
                    'options' => $options
				]);
			?>
		</fieldset>
		<fieldset><legend><?= $this->_('person_info'); ?></legend>
            <?php
                echo $field->field(['name'=>'firstname', 'id'=>'firstname', 'label'=>$this->_('firstname'), 'value'=>$firstname]);
                echo $field->field(['name'=>'lastname',  'id'=>'lastname',  'label'=>$this->_('lastname' ), 'value'=>$lastname ]);
				echo $field->field(['name'=>'email',     'id'=>'email',     'label'=>$this->_('email'    ), 'value'=>$email, 'type'=>'email']);

				$helper = $this->template->getHelper('saveAndCancelButtons');
				echo $helper->saveAndCancelButtons(self::generateUri('users.index'));
            ?>
		</fieldset>
	</form>
</section>
