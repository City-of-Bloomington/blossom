<?php
/**
 * @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 */
	/**
	* 	Logs a user out of the system
	*/
	session_destroy();
	Header("Location: ".BASE_URL);
?>