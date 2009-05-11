<?php
/**
 * @copyright 2006-2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
include '../configuration.inc';
$pdo = Database::getConnection();

foreach (Database::getTables() as $tableName) {
	$fields = array();
	foreach (Database::getFields($tableName) as $row) {
		$type = preg_replace("/[^a-z]/","",$row['type']);

		// Translate database datatypes into PHP datatypes
		if (preg_match('/int/',$type)) {
			$type = 'int';
		}
		if (preg_match('/enum/',$type) || preg_match('/varchar/',$type)) {
			$type = 'string';
		}

		$fields[] = array('field'=>$row['field'],'type'=>$type);
	}

	// Only generate code for tables that have a single-column primary key
	// Code for other tables will need to be created by hand
	$primary_keys = Database::getPrimaryKeyInfo($tableName);
	if (count($primary_keys) != 1) {
		continue;
	}
	$key = $primary_keys[0];


	$className = Inflector::classify($tableName);
	$variableName = Inflector::singularize($tableName);

	/**
	 * Generate the list block
	 */
	$getId = "get".ucwords($key['column_name']);
	$HTML = "<div class=\"interfaceBox\">
	<h1>
		<?php
			if (userHasRole('Administrator')) {
				echo \"<a class=\\\"add button\\\" href=\\\"\".BASE_URL.\"/$tableName/add$className.php\\\">Add</a>\";
			}
		?>
		{$className}s
	</h1>
	<ul><?php
			foreach (\$this->{$variableName}List as \${$variableName}) {
				\$editButton = '';
				if (userHasRole('Administrator')) {
					\$url = new URL(BASE_URL.'/$tableName/update$className.php');
					\$url->$key[column_name] = \${$variableName}->{$getId}();
					\$editButton = \"<a class=\\\"edit button\\\" href=\\\"\$url\\\">Edit</a>\";
				}
				echo \"<li>\$editButton \$$variableName</li>\";
			}
		?>
	</ul>
</div>";

$contents = "<?php\n";
$contents.= COPYRIGHT;
$contents.="
?>
$HTML";

	$dir = APPLICATION_HOME."/scripts/stubs/blocks/$tableName";
	if (!is_dir($dir)) {
		mkdir($dir,0770,true);
	}
	file_put_contents("$dir/{$variableName}List.inc",$contents);


/**
 * Generate the addForm
 */
$HTML = "<h1>Add $className</h1>
<form method=\"post\" action=\"<?php echo \$_SERVER['SCRIPT_NAME']; ?>\">
	<fieldset><legend>$className Info</legend>
		<table>
";
		foreach ($fields as $field) {
			if ($field['field'] != $key['column_name']) {
				$fieldFunctionName = ucwords($field['field']);
				switch ($field['type']) {
					case 'date':
					$HTML.="
			<tr><td><label for=\"{$variableName}-$field[field]-mon\">$field[field]</label></td>
				<td><select name=\"{$variableName}[$field[field]][mon]\" id=\"{$variableName}-$field[field]-mon\">
						<option></option>
						<?php
							\$now = getdate();
							for (\$i=1; \$i<=12; \$i++) {
								\$selected = (\$i==\$now['mon']) ? 'selected=\"selected\"' : '';
								echo \"<option \$selected>\$i</option>\";
							}
						?>
					</select>
					<select name=\"{$variableName}[$field[field]][mday]\">
						<option></option>
						<?php
							for (\$i=1; \$i<=31; \$i++) {
								\$selected = (\$i==\$now['mday']) ? 'selected=\"selected\"' : '';
								echo \"<option \$selected>\$i</option>\";
							}
						?>
					</select>
					<input name=\"{$variableName}[$field[field]][year]\" id=\"{$variableName}-$field[field]-year\" size=\"4\" maxlength=\"4\" value=\"<?php echo \$now['year']; ?>\" />
				</td>
			</tr>";
						break;

					case 'datetime':
					case 'timestamp':
					$HTML.="
			<tr><td><label for=\"{$variableName}-$field[field]-mon\">$field[field]</label></td>
				<td><select name=\"{$variableName}[$field[field]][mon]\" id=\"{$variableName}-$field[field]-mon\">
						<option></option>
						<?php
							\$now = getdate();
							for (\$i=1; \$i<=12; \$i++) {
								\$selected = (\$i==\$now['mon']) ? 'selected=\"selected\"' : '';
								echo \"<option \$selected>\$i</option>\";
							}
						?>
					</select>
					<select name=\"{$variableName}[$field[field]][mday]\">
						<option></option>
						<?php
							for (\$i=1; \$i<=31; \$i++) {
								\$selected = (\$i==\$now['mday']) ? 'selected=\"selected\"' : '';
								echo \"<option \$selected>\$i</option>\";
							}
						?>
					</select>
					<input name=\"{$variableName}[$field[field]][year]\" id=\"{$variableName}-$field[field]-year\" size=\"4\" maxlength=\"4\" value=\"<?php echo \$now['year']; ?>\" />
					<select name=\"{$variableName}[$field[field]][hours]\" id=\"{$variableName}-$field[field]-hours\">
						<?php
							for (\$i=0; \$i<=23; \$i++) {
								\$selected = (\$i==\$now['hours']) ? 'selected=\"selected\"' : '';
								echo \"<option \$selected>\$i</option>\";
							}
						?>
					</select>
					<select name=\"{$variableName}[$field[field]][minutes]\" id=\"{$variableName}-$field[field]-minutes\">
						<?php
							for (\$i=0; \$i<=59; \$i+=15) {
								\$selected = (\$i==\$now['minutes']) ? 'selected=\"selected\"' : '';
								echo \"<option \$selected>\$i</option>\";
							}
						?>
					</select>
				</td>
			</tr>";
						break;

					case 'text':
				$HTML.= "
			<tr><td><label for=\"{$variableName}-$field[field]\">$field[field]</label></td>
				<td><textarea name=\"{$variableName}[$field[field]]\" id=\"{$variableName}-$field[field]\" rows=\"3\" cols=\"60\"></textarea>
				</td>
			</tr>
				";
						break;

					default:
				$HTML.= "
			<tr><td><label for=\"{$variableName}-$field[field]\">$field[field]</label></td>
				<td><input name=\"{$variableName}[$field[field]]\" id=\"{$variableName}-$field[field]\" />
				</td>
			</tr>
				";
				}
			}
		}
	$HTML.= "
		</table>

		<button type=\"submit\" class=\"submit\">Submit</button>
		<button type=\"button\" class=\"cancel\" onclick=\"document.location.href='<?php echo BASE_URL; ?>/{$variableName}s';\">
			Cancel
		</button>
	</fieldset>
</form>";

$contents = "<?php\n";
$contents.= COPYRIGHT;
$contents.="
?>
$HTML";
file_put_contents("$dir/add{$className}Form.inc",$contents);

/**
 * Generate the Update Form
 */
$HTML = "<h1>Update $className</h1>
<form method=\"post\" action=\"<?php echo \$_SERVER['SCRIPT_NAME']; ?>\">
	<fieldset><legend>$className Info</legend>
		<input name=\"$key[column_name]\" type=\"hidden\" value=\"<?php echo \$this->{$variableName}->{$getId}(); ?>\" />
		<table>
";
		foreach ($fields as $field) {
			if ($field['field'] != $key['column_name']) {
				$fieldFunctionName = ucwords($field['field']);
				switch ($field['type']) {
					case 'date':
					$HTML.="
			<tr><td><label for=\"{$variableName}-$field[field]-mon\">$field[field]</label></td>
				<td><select name=\"{$variableName}[$field[field]][mon]\" id=\"{$variableName}-$field[field]-mon\">
						<option></option>
						<?php
							\$$field[field] = \$this->{$variableName}->dateStringToArray(\$this->{$variableName}->get$fieldFunctionName());
							for (\$i=1; \$i<=12; \$i++) {
								\$selected = (\$i==\$$field[field]['mon']) ? 'selected=\"selected\"' : '';
								echo \"<option \$selected\">\$i</option>\";
							}
						?>
					</select>
					<select name=\"{$variableName}[$field[field]][mday]\">
						<option></option>
						<?php
							for (\$i=1; \$i<=31; \$i++) {
								\$selected = (\$i==\$$field[field]['mday']) ? 'selected=\"selected\"' : '';
								echo \"<option \$selected>\$i</option>\";
							}
						?>
					</select>
					<input name=\"{$variableName}[$field[field]][year]\" id=\"{$variableName}-$field[field]-year\" size=\"4\" maxlength=\"4\" value=\"<?php echo \$$field[field]['year']; ?>\" />
				</td>
			</tr>";
						break;

					case 'datetime':
					case 'timestamp':
					$HTML.="
			<tr><td><label for=\"{$variableName}-$field[field]-mon\">$field[field]</label></td>
				<td><select name=\"{$variableName}[$field[field]][mon]\" id=\"{$variableName}-$field[field]-mon\">
						<option></option>
						<?php
							\$$field[field] = \$this->{$variableName}->dateStringToArray(\$this->{$variableName}->get$fieldFunctionName());
							for (\$i=1; \$i<=12; \$i++) {
								\$selected = (\$i==\$$field[field]['mon']) ? 'selected=\"selected\"' : '';
								echo \"<option \$selected>\$i</option>\";
							}
						?>
					</select>
					<select name=\"{$variableName}[$field[field]][mday]\">
						<option></option>
						<?php
							for (\$i=1; \$i<=31; \$i++) {
								\$selected = (\$i==\$$field[field]['mday']) ? 'selected=\"selected\"' : '';
								echo \"<option \$selected>\$i</option>\";
							}
						?>
					</select>
					<input name=\"{$variableName}[$field[field]][year]\" id=\"{$variableName}-$field[field]-year\" size=\"4\" maxlength=\"4\" value=\"<?php echo \$$field[field]['year']; ?>\" />
					<select name=\"{$variableName}[$field[field]][hours]\" id=\"{$variableName}-$field[field]-hours\">
					<?php
						for (\$i=0; \$i<=23; \$i++) {
							\$selected = (\$i==\$$field[field]['hours']) ? 'selected=\"selected\"' : '';
							echo \"<option \$selected>\$i</option>\";
						}
					?>
					</select>
					<select name=\"{$variableName}[$field[field]][minutes]\" id=\"{$variableName}-$field[field]-minutes\">
					<?php
						for (\$i=0; \$i<=59; \$i+=15) {
							\$selected = (\$i==\$$field[field]['minutes']) ? 'selected=\"selected\"' : '';
							echo \"<option \$selected>\$i</option>\";
						}
					?>
					</select>
				</td>
			</tr>";
						break;

					case 'text':
				$HTML.= "
			<tr><td><label for=\"{$variableName}-$field[field]\">$field[field]</label></td>
				<td><textarea name=\"{$variableName}[$field[field]]\" id=\"{$variableName}-$field[field]\" rows=\"3\" cols=\"60\"><?php echo \$this->{$variableName}->get$fieldFunctionName(); ?></textarea>
				</td>
			</tr>
				";
						break;

					default:
				$HTML.= "
			<tr><td><label for=\"{$variableName}-$field[field]\">$field[field]</label></td>
				<td><input name=\"{$variableName}[$field[field]]\" id=\"{$variableName}-$field[field]\" value=\"<?php echo \$this->{$variableName}->get$fieldFunctionName(); ?>\" />
				</td>
			</tr>
				";
				}
			}
		}
	$HTML.= "
		</table>

		<button type=\"submit\" class=\"submit\">Submit</button>
		<button type=\"button\" class=\"cancel\" onclick=\"document.location.href='<?php echo BASE_URL; ?>/{$variableName}s';\">
			Cancel
		</button>
	</fieldset>
</form>";
$contents = "<?php\n";
$contents.= COPYRIGHT;
$contents.="
?>
$HTML";
file_put_contents("$dir/update{$className}Form.inc",$contents);

echo "$className\n";
}
