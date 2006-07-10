<?php
/**
* @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
* This file is part of the City of Bloomington's web application Framework.
* This Framework is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* This Framework is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Foobar; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
*/
/*
	Logs a user into the system.
	A logged in user will have a $_SESSION['USER']
								$_SESSION['IP_ADDRESS']
								$_SESSION['APPLICATION_NAME']


	$_POST Variables:	username
						password
						returnURL
*/
	try
	{
		$user = new User($_POST['username']);

		if ($user->authenticate($_POST['password'])) { $user->startNewSession(); }
		else { throw new Exception("wrongPassword"); }
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: ".BASE_URL);
		exit();
	}

	Header("Location: $_POST[returnURL]");
?>