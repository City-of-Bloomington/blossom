<?php
include("../configuration.inc");

$tables = array();
foreach($PDO->query("show tables") as $row) { list($tables[]) = $row; }

foreach($tables as $tableName)
{
	$fields = array();
	foreach($PDO->query("describe $tableName") as $row)
	{
		$type = ereg_replace("[^a-z]","",$row['Type']);

		if (ereg("int",$type)) { $type = "int"; }
		if (ereg("enum",$type) || ereg("varchar",$type)) { $type = "string"; }


		$fields[] = array('Field'=>$row['Field'],'Type'=>$type);
	}

	$result = $PDO->query("show index from $tableName where key_name='PRIMARY'")->fetchAll();
	if (count($result) != 1) { continue; }
	$key = $result[0];


	$className = Inflector::classify($tableName);
	#--------------------------------------------------------------------------
	# Constructor
	#--------------------------------------------------------------------------
	$constructor = "
		/**
		 * This will load all fields in the table as properties of this class.
		 * You may want to replace this with, or add your own extra, custom loading
		 */
		public function __construct(\$$key[Column_name]=null)
		{
			global \$PDO;

			if (\$$key[Column_name])
			{
				\$sql = 'select * from $tableName where $key[Column_name]=?';
				try
				{
					\$query = \$PDO->prepare(\$sql);
					\$query->execute(array(\$$key[Column_name]));
				}
				catch (Exception \$e) { throw \$e; }

				\$result = \$query->fetchAll();
				foreach(\$result[0] as \$field=>\$value) { if (\$value) \$this->\$field = \$value; }
			}
			else
			{
				# This is where the code goes to generate a new, empty instance.
				# Set any default values for properties that need it here
			}
		}
	";

	#--------------------------------------------------------------------------
	# Properties
	#--------------------------------------------------------------------------
	$properties = "";
	$linkedProperties = array();
	foreach($fields as $field)
	{
		$properties.= "\t\tprivate \$$field[Field];\n";

		if (substr($field['Field'],-3) == "_id") { $linkedProperties[] = $field['Field']; }
	}
	if (count($linkedProperties))
	{
		$properties.="\n\n";
		foreach($linkedProperties as $property)
		{
			$field = substr($property,0,-3);
			$properties.= "\t\tprivate \$$field;\n";
		}
	}

	#--------------------------------------------------------------------------
	# Getters
	#--------------------------------------------------------------------------
	$getters = "";
	foreach($fields as $field)
	{
		$fieldFunctionName = ucwords($field['Field']);

		switch ($field['Type'])
		{
			case 'date':
			case 'datetime':
			case 'timestamp':
				$getters.= "\t\tpublic function get$fieldFunctionName(\$format=null)\n\t\t{\n\t\t\tif (\$format && \$this->$field[Field]!=0) return strftime(\$format,strtotime(\$this->$field[Field]));\n\t\t\telse return \$this->$field[Field];\n\t\t}\n";
			break;

			default: $getters.= "\t\tpublic function get$fieldFunctionName() { return \$this->$field[Field]; }\n";
		}
	}
	foreach($linkedProperties as $property)
	{
		$field = substr($property,0,-3);
		$fieldFunctionName = ucwords($field);
		$getters.= "
		public function get$fieldFunctionName()
		{
			if (\$this->$property)
			{
				if (!\$this->$field) { \$this->$field = new $fieldFunctionName(\$this->$property); }
				return \$this->$field;
			}
			else return null;
		}
		";
	}


	#--------------------------------------------------------------------------
	# Setters
	#--------------------------------------------------------------------------
	$setters = "";
	foreach($fields as $field)
	{
		if ($field['Field'] != $key['Column_name'])
		{
			$fieldFunctionName = ucwords($field['Field']);
			switch ($field['Type'])
			{
				case 'int':
					if (in_array($field['Field'],$linkedProperties))
					{
						$property = substr($field['Field'],0,-3);
						$object = ucfirst($property);
						$setters.= "\t\tpublic function set$fieldFunctionName(\$$field[Type]) { \$this->$property = new $object(\$int); \$this->$field[Field] = \$$field[Type]; }\n";
					}
					else
					{
						$setters.= "\t\tpublic function set$fieldFunctionName(\$$field[Type]) { \$this->$field[Field] = ereg_replace(\"[^0-9]\",\"\",\$$field[Type]); }\n";
					}
				break;

				case 'string':
					$setters.= "\t\tpublic function set$fieldFunctionName(\$$field[Type]) { \$this->$field[Field] = trim(\$$field[Type]); }\n";
				break;

				case 'date':
				case 'datetime':
				case 'timestamp':
					$setters.= "\t\tpublic function set$fieldFunctionName(\$$field[Type]) { \$this->$field[Field] = is_array(\$$field[Type]) ? \$this->dateArrayToString(\$$field[Type]) : \$$field[Type]; }\n";
				break;

				case 'float':
					$setters.= "\t\tpublic function set$fieldFunctionName(\$$field[Type]) { \$this->$field[Field] = ereg_replace(\"[^0-9.\-]\",\"\",\$$field[Type]); }\n";
				break;

				default:
					$setters.= "\t\tpublic function set$fieldFunctionName(\$$field[Type]) { \$this->$field[Field] = \$$field[Type]; }\n";
			}
		}
	}
	$setters.= "\n";
	foreach($linkedProperties as $field)
	{
		$property = substr($field,0,-3);
		$object = ucfirst($property);
		$setters.= "\t\tpublic function set$object(\$$property) { \$this->$field = \${$property}->getId(); \$this->$property = \$$property; }\n";
	}

	#--------------------------------------------------------------------------
	# Output the class
	#--------------------------------------------------------------------------
$contents = "<?php\n";
$contents.= COPYRIGHT;
$contents.="
	class $className extends ActiveRecord
	{
$properties

$constructor

		/**
		 * This generates generic SQL that should work right away.
		 * You can replace this \$fields code with your own custom SQL
		 * for each property of this class,
		 */
		public function save()
		{
			# Check for required fields here.  Throw an exception if anything is missing.

			\$fields = array();
";
			foreach($fields as $field)
			{
				if ($field['Field'] != $key['Column_name'])
				{
					$contents.="\t\t\t\$fields['$field[Field]'] = \$this->$field[Field] ? \$this->$field[Field] : null;\n";
				}
			}
$contents.= "
			# Split the fields up into a preparedFields array and a values array.
			# PDO->execute cannot take an associative array for values, so we have
			# to strip out the keys from \$fields
			\$preparedFields = array();
			foreach(\$fields as \$key=>\$value)
			{
				\$preparedFields[] = \"\$key=?\";
				\$values[] = \$value;
			}
			\$preparedFields = implode(\",\",\$preparedFields);


			if (\$this->$key[Column_name]) { \$this->update(\$values,\$preparedFields); }
			else { \$this->insert(\$values,\$preparedFields); }
		}

		private function update(\$values,\$preparedFields)
		{
			global \$PDO;

			\$sql = \"update $tableName set \$preparedFields where $key[Column_name]={\$this->$key[Column_name]}\";
			if (false === \$query = \$PDO->prepare(\$sql)) { \$e = \$PDO->errorInfo(); throw new Exception(\$sql.\$e[2]); }
			if (false === \$query->execute(\$values)) { \$e = \$PDO->errorInfo(); throw new Exception(\$sql.\$e[2]); }
		}

		private function insert(\$values,\$preparedFields)
		{
			global \$PDO;

			\$sql = \"insert $tableName set \$preparedFields\";
			if (false === \$query = \$PDO->prepare(\$sql)) { \$e = \$PDO->errorInfo(); throw new Exception(\$sql.\$e[2]); }
			if (false === \$query->execute(\$values)) { \$e = \$PDO->errorInfo(); throw new Exception(\$sql.\$e[2]); }
			\$this->$key[Column_name] = \$PDO->lastInsertID();
		}

		/**
		 * Generic Getters
		 */
$getters
		/**
		 * Generic Setters
		 */
$setters
	}
?>";
	$dir = APPLICATION_HOME.'/scripts/stubs/classes';
	if (!is_dir($dir)) { mkdir($dir,0770,true); }
	file_put_contents("$dir/$className.inc",$contents);
	echo "$className\n";
}
?>