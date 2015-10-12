<?php
/**
 * @copyright 2006-2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param User $this->user
 */
use Application\Models\Person;
use Blossom\Classes\View;

$title = $this->user->getId() ? $this->_('user_edit') : $this->_('user_add');
?>
<section>
    <h1><?= $title; ?></h1>
	<form method="post" action="<?= self::generateUri('users.update'); ?>">
		<fieldset><legend><?= $this->_('user_info'); ?></legend>
			<input name="id" type="hidden" value="<?= $this->user->getId(); ?>" />

            <dl><dt><label  for="authenticationMethod"><?= $this->_('authenticationMethod'); ?></label></dt>
                <dd><select name="authenticationMethod" id="authenticationMethod" required="required">
                    <?php
                        foreach (Person::getAuthenticationMethods() as $method) {
                            $selected = $this->user->getAuthenticationMethod()==$method
                                ? 'selected="selected"'
                                : '';
                            echo "<option $selected>$method</option>";
                        }
                    ?>
                    </select>
                </dd>
            </dl>
            <dl><dt><label  for="username"><?= $this->_('username'); ?></label></dt>
                <dd><input name="username" id="username" value="<?= View::escape($this->user->getUsername()); ?>"
                        required="required" />
                </dd>
            </dl>
            <dl><dt><label  for="password"><?= $this->_('password'); ?></label></dt>
                <dd><input name="password" id="password" type="password" /></dd>
            </dl>
            <dl><dt><label  for="role"><?= $this->_('role'); ?></label></dt>
                <dd><select name="role" id="role"><option></option>
                    <?php
                        global $ZEND_ACL;
                        foreach (array_reverse($ZEND_ACL->getRoles()) as $role) {
                            $selected = $this->user->getRole()==$role
                                ? 'selected="selected"'
                                : '';
                            echo "<option $selected>$role</option>";
                        }
                    ?>
                    </select>
                </dd>
            </dl>
			<?php
				$helper = $this->template->getHelper('saveAndCancelButtons');
				echo $helper->saveAndCancelButtons(self::generateUri('users.index'));
			?>
		</fieldset>
	</form>
</section>
