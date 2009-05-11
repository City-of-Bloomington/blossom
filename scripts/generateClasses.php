<?php
/**
 * Generates ActiveRecord class files for each of the database tables
 *
 * @copyright 2006-2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
include '../configuration.inc';
$PDO = Database::getConnection();

foreach (Database::getTables() as $tableName) {
	$fields = array();
	foreach (Database::getFields($tableName) as $row) {
		$type = preg_replace("/[^a-z]/","",strtolower($row['type']));

		// Translate database datatypes into PHP datatypes
		if (preg_match('/int/',$type)) {
			$type = 'int';
		}
		if (preg_match('/enum/',$type) || preg_match('/varchar/',$type)) {
			$type = 'string';
		}

		$fields[] = array('field'=>strtolower($row['field']),'type'=>$type);
	}

	// Only generate code for tables that have a single-column primary key
	// Code for other tables will need to be created by hand
	$primary_keys = Database::getPrimaryKeyInfo($tableName);
	if (count($primary_keys) != 1) {
		continue;
	}
	$key = strtolower($primary_keys[0]['column_name']);

	$tableName = strtolower($tableName);
	$className = Inflector::classify($tableName);
	//--------------------------------------------------------------------------
	// Constructor
	//--------------------------------------------------------------------------
	$constructor = "
	/**
	 * This will load all fields in the table as properties of this class.
	 * You may want to replace this with, or add your own extra, custom loading
	 *
	 * @param int \$$key
	 */
	public function __construct(\$$key=null)
	{
		if (\$$key) {
			\$PDO = Database::getConnection();
			\$query = \$PDO->prepare('select * from $tableName where $key=?');
			\$query->execute(array(\$$key));

			\$result = \$query->fetchAll(PDO::FETCH_ASSOC);
			if (!count(\$result)) {
				throw new Exception('$tableName/unknown$className');
			}
			foreach (\$result[0] as \$field=>\$value) {
				if (\$value) {
					\$this->\$field = \$value;
				}
			}
		}
		else {
			// This is where the code goes to generate a new, empty instance.
			// Set any default values for properties that need it here
		}
	}
	";

	//--------------------------------------------------------------------------
	// Properties
	//--------------------------------------------------------------------------
	$properties = '';
	$linkedProperties = array();
	foreach ($fields as $field) {
		$properties.= "\tprivate \$$field[field];\n";

		if (substr($field['field'],-3) == '_id') {
			$linkedProperties[] = $field['field'];
		}
	}

	if (count($linkedProperties)) {
		$properties.="\n\n";
		foreach ($linkedProperties as $property) {
			$field = substr($property,0,-3);
			$properties.= "\tprivate \$$field;\n";
		}
	}

	//--------------------------------------------------------------------------
	// Getters
	//--------------------------------------------------------------------------
	$getters = '';
	foreach ($fields as $field) {
		$fieldFunctionName = ucwords($field['field']);

		switch ($field['type'])
		{
			case 'date':
			case 'datetime':
			case 'timestamp':
				$getters.= "
	/**
	 * Returns the date/time in the desired format
	 * Format can be specified using either the strftime() or the date() syntax
	 *
	 * @param string \$format
	 */
	public function get$fieldFunctionName(\$format=null)
	{
		if (\$format && \$this->$field[field]) {
			if (strpos(\$format,'%')!==false) {
				return strftime(\$format,\$this->$field[field]);
			}
			else {
				return date(\$format,\$this->$field[field]);
			}
		}
		else {
			return \$this->$field[field];
		}
	}
";
			break;

			default: $getters.= "
	/**
	 * @return $field[type]
	 */
	public function get$fieldFunctionName()
	{
		return \$this->$field[field];
	}
";
		}
	}

	foreach ($linkedProperties as $property) {
		$field = substr($property,0,-3);
		$fieldFunctionName = ucwords($field);
		$getters.= "
	/**
	 * @return $fieldFunctionName
	 */
	public function get$fieldFunctionName()
	{
		if (\$this->$property) {
			if (!\$this->$field) {
				\$this->$field = new $fieldFunctionName(\$this->$property);
			}
			return \$this->$field;
		}
		return null;
	}
";
	}


	//--------------------------------------------------------------------------
	// Setters
	//--------------------------------------------------------------------------
	$setters = '';
	foreach ($fields as $field) {
		if ($field['field'] != $key) {
			$fieldFunctionName = ucwords($field['field']);
			switch ($field['type']) {
				case 'int':
					if (in_array($field['field'],$linkedProperties)) {
						$property = substr($field['field'],0,-3);
						$object = ucfirst($property);
						$setters.= "
	/**
	 * @param $field[type] \$$field[type]
	 */
	public function set$fieldFunctionName(\$$field[type])
	{
		\$this->$property = new $object(\$int);
		\$this->$field[field] = \$$field[type];
	}
";
					}
					else {
						$setters.= "
	/**
	 * @param $field[type] \$$field[type]
	 */
	public function set$fieldFunctionName(\$$field[type])
	{
		\$this->$field[field] = preg_replace(\"/[^0-9]/\",\"\",\$$field[type]);
	}
";
					}
					break;

				case 'string':
					$setters.= "
	/**
	 * @param $field[type] \$$field[type]
	 */
	public function set$fieldFunctionName(\$$field[type])
	{
		\$this->$field[field] = trim(\$$field[type]);
	}
";
					break;

				case 'date':
				case 'datetime':
				case 'timestamp':
	$setters.= "
	/**
	 * Sets the date
	 *
	 * Dates and times should be stored as timestamps internally.
	 * This accepts dates and times in multiple formats and sets the internal timestamp
	 * Accepted formats are:
	 * 		array - in the form of PHP getdate()
	 *		timestamp
	 *		string - anything strtotime understands
	 * @param $field[type] \$$field[type]
	 */
	public function set$fieldFunctionName(\$$field[type])
	{
		if (is_array(\$$field[type])) {
			\$this->$field[field] = \$this->dateArrayToTimestamp(\$$field[type]);
		}
		elseif (ctype_digit(\$$field[type])) {
			\$this->$field[field] = \$$field[type];
		}
		else {
			\$this->$field[field] = strtotime(\$$field[type]);
		}
	}
";
					break;

				case 'float':
					$setters.= "
	/**
	 * @param $field[type] \$$field[type]
	 */
	public function set$fieldFunctionName(\$$field[type])
	{
		\$this->$field[field] = preg_replace(\"/[^0-9.\-]/\",\"\",\$$field[type]);
	}
";
					break;

				case 'bool':
					$setters.= "
	/**
	 * @param boolean \$$field[type]
	 */
	public function set$fieldFunctionName(\$$field[type])
	{
		\$this->$field[field] = \$$field[type] ? true : false;
	}
";
					break;

				default:
					$setters.= "
	/**
	 * @param $field[type] \$$field[type]
	 */
	public function set$fieldFunctionName(\$$field[type])
	{
		\$this->$field[field] = \$$field[type];
	}
";
			}
		}
	}

	foreach ($linkedProperties as $field) {
		$property = substr($field,0,-3);
		$object = ucfirst($property);
		$setters.= "
	/**
	 * @param $object \$$property
	 */
	public function set$object(\$$property)
	{
		\$this->$field = \${$property}->getId();
		\$this->$property = \$$property;
	}
";
	}

	//--------------------------------------------------------------------------
	// Output the class
	//--------------------------------------------------------------------------
$contents = "<?php\n";
$contents.= COPYRIGHT;
$contents.= "
class $className extends ActiveRecord
{
$properties

$constructor
	/**
	 * Throws an exception if anything's wrong
	 * @throws Exception \$e
	 */
	public function validate()
	{
		// Check for required fields here.  Throw an exception if anything is missing.

	}

	/**
	 * Saves this record back to the database
	 *
	 * This generates generic SQL that should work right away.
	 * You can replace this \$fields code with your own custom SQL
	 * for each property of this class,
	 */
	public function save()
	{
		\$this->validate();

		\$fields = array();
";
			foreach ($fields as $field) {
				if ($field['field'] != $key) {
					$contents.="\t\t\$fields['$field[field]'] = \$this->$field[field] ? \$this->$field[field] : null;\n";
				}
			}
$contents.= "
		// Split the fields up into a preparedFields array and a values array.
		// PDO->execute cannot take an associative array for values, so we have
		// to strip out the keys from \$fields
		\$preparedFields = array();
		foreach (\$fields as \$key=>\$value) {
			\$preparedFields[] = \"\$key=?\";
			\$values[] = \$value;
		}
		\$preparedFields = implode(\",\",\$preparedFields);


		if (\$this->$key) {
			\$this->update(\$values,\$preparedFields);
		}
		else {
			\$this->insert(\$values,\$preparedFields);
		}
	}

	private function update(\$values,\$preparedFields)
	{
		\$PDO = Database::getConnection();

		\$sql = \"update $tableName set \$preparedFields where $key={\$this->$key}\";
		\$query = \$PDO->prepare(\$sql);
		\$query->execute(\$values);
	}

	private function insert(\$values,\$preparedFields)
	{
		\$PDO = Database::getConnection();

		\$sql = \"insert $tableName set \$preparedFields\";
		\$query = \$PDO->prepare(\$sql);
		\$query->execute(\$values);
		\$this->$key = \$PDO->lastInsertID();
	}

	//----------------------------------------------------------------
	// Generic Getters
	//----------------------------------------------------------------
$getters
	//----------------------------------------------------------------
	// Generic Setters
	//----------------------------------------------------------------
$setters

	//----------------------------------------------------------------
	// Custom Functions
	// We recommend adding all your custom code down here at the bottom
	//----------------------------------------------------------------
}
";
	$dir = APPLICATION_HOME.'/scripts/stubs/classes';
	if (!is_dir($dir)) {
		mkdir($dir,0770,true);
	}
	file_put_contents("$dir/$className.php",$contents);
	echo "$className\n";
}
