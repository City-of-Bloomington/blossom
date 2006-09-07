<?php
/**
* @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
* This file is part of the City of Bloomington's web application Scaffolding.
* This Scaffolding is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* This Scaffolding is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this scaffolding; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
*/
?>
<div class="menuBar">
	<?php if (isset($_SESSION['USER'])) { echo "<a href=\"".BASE_URL."/logout.php\">Logout</a>"; } ?>
</div>
