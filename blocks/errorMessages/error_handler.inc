<?php
/**
 * @copyright Copyright (C) 2007 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param int $this->errno
 * @param string $this->errstr
 * @param string $this->errfile
 * @param int $this->errline
 * This is the markup for generic PHP error messages
 */
?>
<div id="errorMessages">
<p><em>from <?php echo ADMINISTRATOR_NAME; ?>:</em>
		There is an error in the code on this page that is through no fault of your own.
		Errors of this sort need to be fixed immediately, though.
		Please help me out by copying and pasting the following error message into an email and sending it to me at
		<a href="mailto:<?php echo ADMINISTRATOR_EMAIL; ?>"><?php echo ADMINISTRATOR_EMAIL; ?></a>.</p>
<p><strong>Code Error:</strong>  Error on line <?php echo $this->errline; ?> of file <?php echo $this->errfile; ?>:</p>
<p><?php echo $this->errstr; ?></p>
</div>