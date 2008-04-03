<?php
include APPLICATION_HOME.'/includes/xhtmlHeader.inc';
include APPLICATION_HOME.'/includes/banner.inc';
include APPLICATION_HOME.'/includes/menubar.inc';
include APPLICATION_HOME.'/includes/sidebar.inc';
?>
<div id="mainContent">
	<?php
		include FRAMEWORK.'/errorMessages.inc';

		echo $this->includeBlocks();
	?>
</div>
<?php
include APPLICATION_HOME.'/includes/footer.inc';
include APPLICATION_HOME.'/includes/xhtmlFooter.inc';
?>
