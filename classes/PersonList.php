<?php
/**
 * A collection class for Person objects
 *
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class PersonList extends ZendDbResultIterator
{
	/**
	 * @param array $fields
	 */
	public function __construct($fields=null)
	{
		parent::__construct();
		if (is_array($fields)) {
			$this->find($fields);
		}
	}

	/**
	 * @param array $fields
	 * @param string $sort
	 * @param string $limit
	 * @param string $groupBy
	 */
	public function find($fields=null,$sort='lastname',$limit=null,$groupBy=null)
	{
		$this->select->from('people');

		if (count($fields)) {
			foreach ($fields as $key=>$value) {
				$this->select->where("$key=?",$value);
			}
		}

		$this->populateList();
	}

	/**
	 * Hydrates all the objects from a database result set
	 *
	 * @return array An array of Role objects
	 */
	protected function loadResult($key)
	{
		return new Person($this->result[$key]);
	}
}
