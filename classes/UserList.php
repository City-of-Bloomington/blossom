<?php
/**
 * A collection class for User objects
 *
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class UserList extends ZendDbResultIterator
{
	private $columns;

	/**
	 * @param array $fields
	 */
	public function __construct($fields=null)
	{
		parent::__construct();
		$this->columns = $this->zend_db->describeTable('users');

		if (is_array($fields)) {
			$this->find($fields);
		}
	}

	/**
	 * Populates the collection
	 *
	 * @param array $fields
	 * @param string|array $order Multi-column sort should be given as an array
	 * @param int $limit
	 * @param string|array $groupBy Multi-column group by should be given as an array
	 */
	public function find($fields=null,$order='username',$limit=null,$groupBy=null)
	{
		$this->select->from(array('u'=>'users'));

		if (count($fields)) {
			foreach ($fields as $key=>$value) {
				if (array_key_exists($key,$this->columns)) {
					$this->select->where("u.$key=?",$value);
				}
			}
		}

		// Finding on fields from other tables required joining those tables.
		// You can add fields from other tables to $options by adding the join SQL
		// to $this->joins here
		$joins = array();

		if (isset($fields['firstname'])) {
			$joins['p'] = array('table'=>'people','condition'=>'u.id=p.user_id');
			$this->select->where('p.firstname=?',$fields['firstname']);
		}
		if (isset($fields['lastname'])) {
			$joins['p'] = array('table'=>'people','condition'=>'u.id=p.user_id');
			$this->select->where('p.lastname=?',$fields['lastname']);
		}
		if (isset($fields['email'])) {
			$joins['p'] = array('table'=>'people','condition'=>'u.id=p.user_id');
			$this->select->where('p.email=?',$fields['email']);
		}
		if (isset($fields['role'])) {
			$joins['ur'] = array('table'=>'user_roles','condition'=>'u.id=ur.user_id');
			$joins['r'] = array('table'=>'roles','condition'=>'ur.role_id=r.id');
			$this->select->where('r.name=?',$fields['role']);
		}

		foreach ($joins as $key=>$join) {
			$this->select->joinLeft(array($key=>$join['table']),$join['condition']);
		}



		$this->select->order($order);
		if ($limit) {
			$this->select->limit($limit);
		}
		if ($groupBy) {
			$this->select->group($groupBy);
		}
		$this->populateList();
	}

	/**
	 * Hydrates all the objects from a database result set
	 *
	 * @return array An array of objects
	 */
	protected function loadResult($key)
	{
		return new User($this->result[$key]);
	}
}
