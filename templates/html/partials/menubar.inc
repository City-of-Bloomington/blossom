<nav>
	<?php
		$a = '<a href="%s">%s</a>';
		echo sprintf($a, BASE_URI, $this->translate('labels.home'));
		if (isset($_SESSION['USER'])) {
			echo sprintf($a, BASE_URI.'/login/logout', $this->translate('labels.logout'));
		}
		else {
			echo sprintf($a, BASE_URI."/login?return_url=$_SERVER[REQUEST_URI]", $this->translate('labels.login'));
		}
	?>
</nav>
