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
	private static $connection;

	/**
	 * @param boolean $reconnect If true, drops the connection and reconnects
	 * @return resource
	 */
	public static function getConnection($reconnect=false)
	{
		if ($reconnect) {
			self::$connection=null;
		}
		if (!self::$connection) {
			try {
				$parameters = array('host'=>DB_HOST,
									'username'=>DB_USER,
									'password'=>DB_PASS,
									'dbname'=>DB_NAME,
									'options'=>array(Zend_Db::CASE_FOLDING=>Zend_Db::CASE_LOWER,
													 Zend_Db::AUTO_QUOTE_IDENTIFIERS=>false));
				self::$connection = Zend_Db::factory(DB_ADAPTER,$parameters);
				self::$connection->getConnection();

				// Alter oracle sessions to act more like MySQL
				if (self::getType() == 'oracle') {
					self::$connection->query('alter session set current_schema=?',DB_USER);
					self::$connection->query('alter session set nls_date_format=?','YYYY-MM-DD HH24:MI:SS');
					self::$connection->query('alter session set nls_comp=linguistic');
					self::$connection->query('alter session set nls_sort=binary_ci');
				}
			}
			catch (Exception $e) {
				die($e->getMessage());
			}
		}
		return self::$connection;
	}

	/**
	 * Returns the type of database that's being used (mysql, oracle, etc.)
	 *
	 * @return string
	 */
	public static function getType()
	{
		switch (strtolower(DB_ADAPTER)) {
			case 'pdo_mysql':
			case 'mysqli':
				return 'mysql';
				break;

			case 'pdo_oci':
			case 'oci8':
				return 'oracle';
				break;
		}

	}
}
