<?php
$copyright = "/**
 * @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 */";

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
		public function __construct(\$fields=null,\$sort=\"id\")
		{
			\$this->select = \"select $tableName.$key[Column_name] as id from $tableName\";
			\$this->sort = \$sort;
			if (is_array(\$fields)) \$this->find(\$fields);
		}
	";


	#--------------------------------------------------------------------------
	# Find
	#--------------------------------------------------------------------------
	$findFunction = "
		public function find(\$fields=null,\$sort=\"$key[Column_name]\")
		{
			\$this->sort = \$sort;

			\$options = array();
";
			foreach($fields as $field) { $findFunction.="\t\t\tif (isset(\$fields['$field[Field]'])) { \$options[] = \"$field[Field]='\$fields[$field[Field]]'\"; }\n"; }
	$findFunction.="

			# Finding on fields from other tables required joining those tables.
			# You can add fields from other tables to \$options by adding the join SQL
			# to \$this->joins here

			\$this->populateList(\$options);
		}
	";



	#--------------------------------------------------------------------------
	# Output the class
	#--------------------------------------------------------------------------
$contents = "<?php
$copyright
	class {$className}List extends PDOResultIterator
	{
		public function __construct(\$fields=null,\$sort=\"id\")
		{
			\$this->select = \"select $tableName.id from $tableName\";
			\$this->sort = \$sort;
			if (is_array(\$fields)) \$this->find(\$fields);
		}

$findFunction

		protected function loadResult(\$key) { return new $className(\$this->list[\$key]); }
	}
?>";
	echo "$className\n";
	file_put_contents("./classStubs/{$className}List.inc",$contents);
}
?>