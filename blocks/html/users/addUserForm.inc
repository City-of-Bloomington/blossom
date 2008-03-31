<h1>New User</h1>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Login Info</legend>
	<table>
	<tr><td><label for="user-authenticationMethod">Authentication</label></td>
		<td><select name="user[authenticationMethod]" id="user-authenticationMethod">
				<option <?php if(isset($_POST['user']['authenticationMethod']) && $_POST['user']['authenticationMethod']=="LDAP") echo "selected=\"selected\""; ?>>LDAP</option>
				<option <?php if(isset($_POST['user']['authenticationMethod']) && $_POST['user']['authenticationMethod']=="local") echo "selected=\"selected\""; ?>>local</option>
			</select>
		</td>
	</tr>
	<tr><td><label for="user-username">Username</label></td>
		<td><input name="user[username]" id="user-username" value="<?php if(isset($_POST['user']['username'])) echo $_POST['user']['username']; ?>" /></td></tr>
	<tr><td><label for="user-password">Password</label></td>
		<td><input name="user[password]" id="user-password" /></td></tr>
	<tr><td><label for="user[roles]">Roles</label></td>
		<td><select name="user[roles][]" id="user-roles" size="5" multiple="multiple">
			<?php
				$roles = new RoleList();
				$roles->find();
				foreach($roles as $role)
				{
					if (isset($_POST['user']['roles']) && in_array($role,$_POST['user']['roles']))
					{
						echo "<option selected=\"selected\">$role</option>";
					}
					echo "<option>$role</option>";
				}
			?>
			</select>
		</td>
	</tr>
	</table>

</fieldset>
<fieldset><legend>Personal Info</legend>
	<p>This information is only necessary for local users.  For LDAP authentication, this information will be pulled from LDAP.</p>
	<table>
	<tr><td><label for="user-firstname">Firstname</label></td>
		<td><input name="user[firstname]" id="user-firstname" value="<?php if(isset($_POST['user']['firstname'])) echo $_POST['user']['firstname']; ?>" /></td></tr>
	<tr><td><label for="user-lastname">Lastname</label></td>
		<td><input name="user[lastname]" id="user-lastname" value="<?php if(isset($_POST['user']['lastname'])) echo $_POST['user']['lastname']; ?>" /></td></tr>
	</table>

	<button type="submit" class="submit">Submit</button>
	<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/users';">Cancel</button>
</fieldset>
</form>