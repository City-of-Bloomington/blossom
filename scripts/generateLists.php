<?php
$copyright = "/**
* @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
* This file is part of the City of Bloomington's web application Framework.
* This Framework is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* This Framework is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Foobar; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
*/";


mysql_connect(":/tmp/mysql.sock","username","password") or die(mysql_error());
mysql_select_db("database") or die(mysql_error());

$sql = "show tables";
$tables = mysql_query($sql) or die($sql.mysql_error());
while(list($tableName) = mysql_fetch_array($tables))
{
	$className = ucwords($tableName);
	echo "$className\n";

	$sql = "describe $tableName";
	$description = mysql_query($sql) or die($sql.mysql_error());
	$fields = array();
	while($row = mysql_fetch_array($description))
	{
		$type = ereg_replace("[^a-z]","",$row['Type']);

		if (ereg("int",$type)) { $type = "int"; }
		if (ereg("enum",$type) || ereg("varchar",$type)) { $type = "string"; }


		$fields[] = array('Field'=>$row['Field'],'Type'=>$type);

		#echo "\t$row[Field] - $type\n";
	}

	$constructor = "";
	$sql = "show index from $tableName where key_name='PRIMARY'";
	$temp = mysql_query($sql) or die($sql.mysql_error());

	# This code should really only be run on tables with a single primary key
	# The other tables are either linking tables, or are multiple attributes of a single-keyed table
	if (mysql_num_rows($temp) != 1) { continue; }
	$key = mysql_fetch_array($temp);



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
		file_put_contents("./classStubs/{$className}List.inc",$contents);
	}
?>