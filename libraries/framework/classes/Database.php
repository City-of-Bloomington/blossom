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
									'options'=>array(Zend_Db::CASE_FOLDING=>Zend_Db::CASE_LOWER));
				self::$connection = Zend_Db::factory(DB_ADAPTER,$parameters);
				self::$connection->getConnection();
			}
			catch (Exception $e) {
				die($e->getMessage());
			}
		}
		return self::$connection;
	}
}
