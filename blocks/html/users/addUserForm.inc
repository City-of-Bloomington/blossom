<?php
/**
 * @copyright 2006-2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<h1>New User</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Login Info</legend>
		<table>
			<tr><td><label for="user-authenticationMethod">Authentication</label></td>
				<td><select name="user[authenticationMethod]" id="user-authenticationMethod">
						<option <?php if(isset($_POST['user']['authenticationMethod']) && $_POST['user']['authenticationMethod']=="LDAP") echo "selected=\"selected\""; ?>>
							LDAP
						</option>
						<option <?php if(isset($_POST['user']['authenticationMethod']) && $_POST['user']['authenticationMethod']=="local") echo "selected=\"selected\""; ?>>
							local
						</option>
					</select>
				</td>
			</tr>
			<tr><td><label for="user-username">Username</label></td>
				<td><input name="user[username]" id="user-username" value="<?php if(isset($_POST['user']['username'])) echo View::escape($_POST['user']['username']); ?>" />
				</td>
			</tr>
			<tr><td><label for="user-password">Password</label></td>
				<td><input name="user[password]" id="user-password" /></td>
			</tr>
			<tr><td><label for="user[roles]">Roles</label></td>
				<td><select name="user[roles][]" id="user-roles" size="5" multiple="multiple">
					<?php
						$roles = new RoleList();
						$roles->find();
						foreach ($roles as $role) {
							$selected = (isset($_POST['user']['roles']) && in_array($role,$_POST['user']['roles']))
										? 'selected="selected"'
										: '';
							echo "<option $selected>$role</option>";
						}
					?>
					</select>
				</td>
			</tr>
		</table>

	</fieldset>

	<fieldset><legend>Personal Info</legend>
		<p>
			This information is only necessary for local users.
			For LDAP authentication, this information will be pulled from LDAP.
		</p>
		<table>
			<tr><td><label for="person-firstname">Firstname</label></td>
				<td><input name="person[firstname]" id="person-firstname" value="<?php if(isset($_POST['person']['firstname'])) echo View::escape($_POST['person']['firstname']); ?>" />
				</td>
			</tr>
			<tr><td><label for="person-lastname">Lastname</label></td>
				<td><input name="person[lastname]" id="person-lastname" value="<?php if(isset($_POST['person']['lastname'])) echo View::escape($_POST['person']['lastname']); ?>" />
				</td>
			</tr>
			<tr><td><label for="person-email">Email</label></td>
				<td><input name="person[email]" id="person-email" value="<?php if(isset($_POST['person']['email'])) echo View::escape($_POST['person']['email']); ?>" />
				</td>
			</tr>
		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/users';">
			Cancel
		</button>
	</fieldset>
</form>
