<?php
/**
 * @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 */
	class UserList extends PDOResultIterator
	{
		public function __construct($fields=null,$sort="username")
		{
			$this->select = "select distinct users.id from users";
			$this->sort = $sort;
			if (is_array($fields)) $this->find($fields);
		}


		public function find($fields=null,$sort="username")
		{
			$this->sort = $sort;

			$options = array();
			if (isset($fields['id'])) { $options[] = "id='$fields[id]'"; }
			if (isset($fields['username'])) { $options[] = "username='$fields[username]'"; }
			if (isset($fields['password'])) { $options[] = "password='$fields[password]'"; }
			if (isset($fields['authenticationMethod'])) { $options[] = "authenticationMethod='$fields[authenticationMethod]'"; }
			if (isset($fields['firstname'])) { $options[] = "firstname='$fields[firstname]'"; }
			if (isset($fields['lastname'])) { $options[] = "lastname='$fields[lastname]'"; }

			# To search on Role, you have to join the userRoles table
			if (isset($fields['role']))
			{
				$this->joins = "left join user_roles on users.id=user_id left join roles on role_id=roles.id";
				$options[] = "role='$fields[role]'";
			}

			$this->populateList($options);
		}

		public function search(array $fields,$sort="username")
		{
			$this->sort = $sort;

			$options = array();
			if (isset($fields['id'])) { $options[] = "id='$fields[id]'"; }
			if (isset($fields['username'])) { $options[] = "username='$fields[username]'"; }
			if (isset($fields['password'])) { $options[] = "password='$fields[password]'"; }
			if (isset($fields['authenticationMethod'])) { $options[] = "authenticationMethod='$fields[authenticationMethod]'"; }
			if (isset($fields['firstname'])) { $options[] = "firstname like '$fields[firstname]%'"; }
			if (isset($fields['lastname'])) { $options[] = "lastname like '$fields[lastname]%'"; }

			# To search on Role, you have to join the userRoles table
			if (isset($fields['role']))
			{
				$this->joins = "left join user_roles on users.id=user_id left join roles on role_id=roles.id";
				$options[] = "role='$fields[role]'";
			}

			$this->populateList($options);
		}

		protected function loadResult($key) { return new User($this->list[$key]); }
	}
?>