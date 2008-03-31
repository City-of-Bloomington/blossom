<?php
/**
 * @copyright Copyright (C) 2007 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * This page should be displayed whenever the user does not have Javascript
 */
	$_SESSION['errorMessages'][] = new Exception('noJavascript');
	$template = new Template();
	$template->render();
?>