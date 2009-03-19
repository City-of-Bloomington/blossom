<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class Person extends ActiveRecord
{
	private $id;
	private $firstname;
	private $lastname;
	private $email;

	private $user_id;
	private $user;
	/**
	 * This will load all fields in the table as properties of this class.
	 * You may want to replace this with, or add your own extra, custom loading
	 *
	 * @param int $id
	 */
	public function __construct($id=null)
	{
		if ($id) {
			if (ctype_digit($id)) {
				$sql = 'select * from people where id=?';
			}
			elseif (false !== strpos($id,'@')) {
				$sql = 'select * from people where email=?';
			}
			else {
				$sql = 'select p.* from people p left join users on p.id=person_id where username=?';
			}

			$pdo = Database::getConnection();
			$query = $pdo->prepare($sql);
			$query->execute(array($id));


			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			if (!count($result)) {
				throw new Exception('people/unknownPerson');
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
	 * Throws an exception if anything's wrong
	 * @throws Exception $e
	 */
	public function validate()
	{
		// Check for required fields here.  Throw an exception if anything is missing.
		if (!$this->firstname || !$this->lastname) {
			throw new Exception('missingRequiredFields');
		}
	}

	/**
	 * Saves this record back to the database
	 *
	 * This generates generic SQL that should work right away.
	 * You can replace this $fields code with your own custom SQL
	 * for each property of this class,
	 */
	public function save()
	{
		$this->validate();

		$fields = array();
		$fields['firstname'] = $this->firstname;
		$fields['lastname'] = $this->lastname;
		$fields['email'] = $this->email ? $this->email : null;

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

		$sql = "update people set $preparedFields where id={$this->id}";
		$query = $PDO->prepare($sql);
		$query->execute($values);
	}

	private function insert($values,$preparedFields)
	{
		$PDO = Database::getConnection();

		$sql = "insert people set $preparedFields";
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
	public function getFirstname()
	{
		return $this->firstname;
	}

	/**
	 * @return string
	 */
	public function getLastname()
	{
		return $this->lastname;
	}

	/**
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	//----------------------------------------------------------------
	// Generic Setters
	//----------------------------------------------------------------
	/**
	 * @param string $string
	 */
	public function setFirstname($string)
	{
		$this->firstname = trim($string);
	}

	/**
	 * @param string $string
	 */
	public function setLastname($string)
	{
		$this->lastname = trim($string);
	}

	/**
	 * @param string $string
	 */
	public function setEmail($string)
	{
		$this->email = trim($string);
	}


	//----------------------------------------------------------------
	// Custom Functions
	// We recommend adding all your custom code down here at the bottom
	//----------------------------------------------------------------
	/**
	 * @return string
	 */
	public function getFullname()
	{
		return "{$this->firstname} {$this->lastname}";
	}

	/**
	 * @return string
	 */
	public function getURL()
	{
		return BASE_URL.'/people/viewPerson.php?person_id='.$this->id;
	}

	/**
	 * @return int
	 */
	public function getUser_id()
	{
		if (!$this->user_id) {
			$pdo = Database::getConnection();
			$query = $pdo->prepare('select id from users where person_id=?');
			$query->execute(array($this->id));
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			if (count($result)) {
				$this->user_id = $result[0]['id'];
			}
		}
		return $this->user_id;
	}

	/**
	 * @return User
	 */
	public function getUser() {
		if (!$this->user) {
			if ($this->getUser_id()) {
				$this->user = new User($this->getUser_id());
			}
		}
		return $this->user;
	}

	/**
	 * @return string
	 */
	public function getUsername() {
		if ($this->getUser()) {
			return $this->getUser()->getUsername();
		}
	}
}
