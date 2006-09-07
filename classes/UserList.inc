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
	class UserList extends PDOResultIterator
	{
		public function find($fields=null,$sort = "username")
		{
			global $PDO;
			$this->sort = $sort;

			if ($fields)
			{
				$options = array();

				if (isset($fields['id'])) { $options[] = "users.id=$fields[id]"; }
				if (isset($fields['username'])) { $options[] = "username='$fields[username]'"; }
				if (isset($fields['authenticationMethod'])) { $options[] = "authenticationMethod='$fields[authenticationMethod]'"; }
				if (isset($fields['firstname'])) { $options[] = "firstname='$fields[firstname]'"; }
				if (isset($fields['lastname'])) { $options[] = "lastname='$fields[lastname]'"; }

				# To search on Role, you have to join the userRoles table
				if (isset($fields['role']))
				{
					$this->joins = "left join user_roles on users.id=user_id left join roles on role_id=roles.id";
					$options[] = "role='$fields[role]'";
				}

				$this->where = "where ".implode(" and ",$options);
				$sql.= "select distinct users.id from users {$this->joins} {$this->where} order by {$this->sort}";
			}
			else { $sql = "select id from users order by {$this->sort}"; }

			$result = $PDO->query($sql);
			if ($result)
			{
				foreach($result as $row) { $this->list[] = $row['id']; }
			}
			else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		public function search($fields=null,$sort = "username")
		{
			global $PDO;

			if ($fields)
			{
				$options = array();

				if (isset($fields['id'])) { $options[] = "users.id=$fields[id]"; }
				if (isset($fields['username'])) { $options[] = "username like '$fields[username]%'"; }
				if (isset($fields['authenticationMethod'])) { $options[] = "authenticationMethod='$fields[authenticationMethod]'"; }
				if (isset($fields['firstname'])) { $options[] = "firstname like '$fields[firstname]%'"; }
				if (isset($fields['lastname'])) { $options[] = "lastname like '$fields[lastname]%'"; }

				# To search on Role, you have to join the userRoles table
				if (isset($fields['role']))
				{
					$this->joins = "left join user_roles on users.id=user_id left join roles on role_id=roles.id";
					$options[] = "role='$fields[role]'";
				}

				$this->where = "where ".implode(" and ",$options);
				$sql.= "select distinct users.id from users {$this->joins} {$this->where} order by {$this->sort}";
			}
			else { $sql = "select id from users order by $sort"; }

			$result = $PDO->query($sql);
			if ($result)
			{
				foreach($result as $row) { $this->list[] = $row['id']; }
			}
			else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		protected function loadResult($key) { return new User($this->list[$key]); }
	}
?>