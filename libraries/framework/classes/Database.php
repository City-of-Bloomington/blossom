<?php
/**
 * Singleton for the Database connection
 *
 * @copyright 2006-2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class Database
{
	private static $pdo;

	/**
	 * @param boolean $reconnect If true, drops the connection and reconnects
	 * @return resource
	 */
	public static function getConnection($reconnect=false)
	{
		if ($reconnect) {
			self::$pdo=null;
		}
		if (!self::$pdo) {
			try {
				self::$pdo = new PDO(DB_DSN,DB_USER,DB_PASS,
										array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
											  PDO::ATTR_CASE=>PDO::CASE_LOWER));
				if (self::$pdo->getAttribute(PDO::ATTR_DRIVER_NAME)=='oci') {
					$query = self::$pdo->prepare('alter session set current_schema=?');
					$query->execute(array(DB_USER));
				}
			}
			catch (PDOException $e) {
				die($e->getMessage());
			}
		}
		return self::$pdo;
	}

	/**
	 * Returns an array of all the tablenames in the database
	 * @return array
	 */
	public static function getTables()
	{
		$pdo = self::getConnection();

		switch ($pdo->getAttribute(PDO::ATTR_DRIVER_NAME)) {
			case 'mysql':
				$sql = 'show tables';
				break;

			case 'oci':
				$sql = 'select table_name from user_tables';
				break;

			default:
				die("unsupported database server\n");
		}

		$tables = array();
		foreach ($pdo->query($sql) as $row) {
			list($tables[]) = $row;
		}

		return $tables;
	}

	/**
	 * Returns a standardized array of column information for the given table
	 *
	 * @return array ('field'=>,'type')
	 */
	public static function getFields($table)
	{
		$pdo = self::getConnection();

		switch ($pdo->getAttribute(PDO::ATTR_DRIVER_NAME)) {
			case 'mysql':
				$sql = "describe $table";
				$parameters = null;
				break;

			case 'oci':
				$sql = "select column_name as field,data_type as type from user_tab_columns
						where table_name=?";
				$parameters = array($table);
				break;

			default:
				die("unsupported database server\n");
		}

		$query = $pdo->prepare($sql);
		$query->execute($parameters);
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * Returns database results about the primary keys for a given table
	 *
	 * Different databases return different results, but all databases
	 * will return at least a column_name field.
	 *
	 * @return array
	 */
	public static function getPrimaryKeyInfo($table)
	{
		$pdo = self::getConnection();

		switch ($pdo->getAttribute(PDO::ATTR_DRIVER_NAME)) {
			case 'mysql':
				$sql = "show index from $table where key_name='PRIMARY'";
				$parameters = null;
				break;

			case 'oci':
				$sql = "select cols.column_name,cols.position
						from user_constraints cons,user_cons_columns cols
						where cons.constraint_type='P'
						and cons.constraint_name=cols.constraint_name
						and cols.table_name=?";
				$parameters = array($table);
				break;

			default:
				die("unsupported database server\n");
		}
		$query = $pdo->prepare($sql);
		$query->execute($parameters);
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
}
