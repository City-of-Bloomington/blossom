<form method="post" action="<?php echo BASE_URL; ?>/login/login.php">
	<fieldset><legend>Login</legend>
		<input name="return_url" type="hidden" value="<?php echo View::escape($_SERVER['REQUEST_URI']); ?>" />
		<table>
			<tr><th><label for="username">Username:</label></th>
				<td><input name="username" id="username" /></td><td></td>
			</tr>
			<tr><th><label for="password">Password:</label></th>
				<td><input type="password" name="password" id="password" /></td>
				<td><button type="submit" class="login">Login</button></td>
			</tr>
		</table>

	</fieldset>
</form>
