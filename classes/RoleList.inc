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
/*
	Roles are really just an attribute array for users.  For now, I'm not loading them in as objects.
*/
	class RoleList extends PDOResultIterator
	{

		public function find($fields=null,$sort='role')
		{
			global $PDO;

			$options = array();
			if (isset($fields['role'])) { $options[] = "role='$fields[role]'"; }

			if (count($options)) { $where = " where ".implode(" and ",$options); } else { $where = ""; }
			$sql = "select role from roles $where order by $sort";

			foreach($PDO->query($sql) as $row) { $this->list[] = $row['role']; }
		}


		protected function loadResult($key) { return $this->list[$key]; }
	}
?>