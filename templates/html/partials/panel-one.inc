<div id="panel-one">
<?php
	if (!isset($_SESSION['USER'])) {
		$block = new Block('loginForm.inc');
		echo $block->render();
	}

	if (userHasRole('Administrator')) {
		include APPLICATION_HOME.'/templates/html/partials/panel-widgets/Administrator.inc';
	}
?>
</div>
