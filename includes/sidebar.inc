<?php
/**
 * @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 */
?>
<div id="sidebar">
	<div>
		<div class="titleBar">Tables</div>
		<ul>
		</ul>
	</div>
	<?php
		if (!isset($_SESSION['USER'])) { include(FRAMEWORK."/sidebarBoxes/loginBox.inc"); }
		else { if (in_array("Administrator",$_SESSION['USER']->getRoles())) { include(APPLICATION_HOME."/includes/sidebarBoxes/Administrator.inc"); } }
	?>
</div>