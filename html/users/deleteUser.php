<?php
/**
 * @copyright Copyright (C) 2006-2008 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET id
 */
verifyUser('Administrator');

$user = new User($_GET['id']);
$user->delete();

Header('Location: '.BASE_URL.'/users');
