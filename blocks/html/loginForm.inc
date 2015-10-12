<?php
use Blossom\Classes\View;
?>
<form method="post" action="<?= self::generateUri('login.login'); ?>">
	<fieldset><legend><?= $this->_('login'); ?></legend>
		<input name="return_url" type="hidden" value="<?= View::escape($this->return_url); ?>" />
		<table>
			<tr><th><label for="username"><?= $this->_('username'); ?></label></th>
				<td><input name="username" id="username" /></td><td></td>
			</tr>
			<tr><th><label for="password"><?= $this->_('password'); ?></label></th>
				<td><input type="password" name="password" id="password" /></td>
			</tr>
		</table>
		<button type="submit" class="login"><?= $this->_('login'); ?></button>
	</fieldset>
</form>
