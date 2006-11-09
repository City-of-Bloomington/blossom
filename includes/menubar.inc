<?php
/**
 * @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 */
?>
<div class="menuBar">
	<ul><li><a href="<?php echo BASE_URL; ?>">Home</a></li>
		<?php if (isset($_SESSION['USER'])) { echo "<li><a href=\"".BASE_URL."/logout.php\">Logout</a></li>"; } ?>
	</ul>
</div>