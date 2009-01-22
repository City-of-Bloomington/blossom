<?php
/**
 * @copyright 2006-2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class Role extends ActiveRecord
{
	private $id;
	private $name;

	/**
	 * This will load all fields in the table as properties of this class.
	 * You may want to replace this with, or add your own extra, custom loading
	 * @param int|string $id
	 */
	public function __construct($id=null)
	{
		if ($id) {
			$PDO = Database::getConnection();

			if (ctype_digit($id)) {
				$sql = 'select * from roles where id=?';
			}
			else {
				$sql = 'select * from roles where name=?';
			}
			$query = $PDO->prepare($sql);
			$query->execute(array($id));

			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			if (!count($result)) {
				throw new Exception('roles/unknownRole');
			}
			foreach ($result[0] as $field=>$value) {
				if ($value) {
					$this->$field = $value;
				}
			}
		}
		else {
			// This is where the code goes to generate a new, empty instance.
			// Set any default values for properties that need it here
		}
	}

	/**
	 * Throws an exception if theres anything wrong
	 * @throws Exception
	 */
	public function validate()
	{
		if (!$this->name) {
			throw new Exception('missingName');
		}
	}

	/**
	 * This generates generic SQL that should work right away.
	 * You can replace this $fields code with your own custom SQL
	 * for each property of this class,
	 */
	public function save()
	{
		$this->validate();

		$fields = array();
		$fields['name'] = $this->name;

		// Split the fields up into a preparedFields array and a values array.
		// PDO->execute cannot take an associative array for values, so we have
		// to strip out the keys from $fields
		$preparedFields = array();
		foreach ($fields as $key=>$value) {
			$preparedFields[] = "$key=?";
			$values[] = $value;
		}
		$preparedFields = implode(",",$preparedFields);


		if ($this->id) {
			$this->update($values,$preparedFields);
		}
		else {
			$this->insert($values,$preparedFields);
		}
	}

	private function update($values,$preparedFields)
	{
		$PDO = Database::getConnection();

		$sql = "update roles set $preparedFields where id={$this->id}";
		$query = $PDO->prepare($sql);
		$query->execute($values);
	}

	private function insert($values,$preparedFields)
	{
		$PDO = Database::getConnection();

		$sql = "insert roles set $preparedFields";
		$query = $PDO->prepare($sql);
		$query->execute($values);
		$this->id = $PDO->lastInsertID();
	}

	//----------------------------------------------------------------
	// Generic Getters
	//----------------------------------------------------------------
	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}
	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	//----------------------------------------------------------------
	// Generic Setters
	//----------------------------------------------------------------
	/**
	 * @param string $string
	 */
	public function setName($string)
	{
		$this->name = trim($string);
	}

	//----------------------------------------------------------------
	// Custom Functions
	// We recommend adding all your custom code down here at the bottom
	//----------------------------------------------------------------
	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->name;
	}
}
