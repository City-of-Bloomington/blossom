<?php
/**
 * @copyright Copyright (C) 2006-2008 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 */
# Calculate the process time
$endTime = microtime(1);
$processTime = $endTime - $startTime;
?>
<div id="footer">
<p>
	<!-- Process Time: <?php echo $processTime; ?> -->
</p>
</div>
