<?php
/**
 * @copyright 2006-2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param User $this->user
 */
?>
<h1>Edit <?php echo $this->user->getUsername(); ?></h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Login Info</legend>
		<input name="user_id" type="hidden" value="<?php echo $this->user->getId(); ?>" />
		<input name="person_id" type="hidden" value="<?php echo $this->user->getPerson_id(); ?>" />
		<table>
			<tr><td><label for="authenticationMethod">Authentication</label></td>
				<td><select name="authenticationMethod" id="authenticationMethod">
						<option <?php if ($this->user->getAuthenticationMethod()=='LDAP') echo "selected=\"selected\""; ?>>
							LDAP
						</option>
						<option <?php if ($this->user->getAuthenticationMethod()=='local') echo "selected=\"selected\""; ?>>
							local
						</option>
					</select>
				</td>
			</tr>
			<tr><td><label for="username">Username</label></td>
				<td><input name="username" id="username" value="<?php echo View::escape($this->user->getUsername()); ?>" />
				</td>
			</tr>
			<tr><td><label for="password">Password</label></td>
				<td><input name="password" id="password" /></td>
			</tr>
			<tr><td><label for="roles">Roles</label></td>
				<td><select name="roles[]" id="roles" size="5" multiple="multiple">
					<?php
						$roles = new RoleList();
						$roles->find();
						foreach ($roles as $role) {
							$selected = in_array($role,$this->user->getRoles())
										? 'selected="selected"'
										: '';
							echo "<option $selected>$role</option>";
						}
					?>
					</select>
				</td>
			</tr>
		</table>
		<?php
			echo $this->formButton('Submit','submit','submit');
			echo $this->formButton(
				'Cancel','button','cancel',null,"document.location.href='".BASE_URL."/users';"
			);
		?>
	</fieldset>
</form>
