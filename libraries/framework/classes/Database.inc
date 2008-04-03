<?php
	abstract class Database
	{
		private static $PDO;

		public static function getConnection($reconnect=false)
		{
			if ($reconnect) { self::$PDO=null; }
			if(!self::$PDO)
			{
				try
				{
					self::$PDO = new PDO('mysql:unix_socket=/tmp/mysql.sock;dbname='.DB_NAME,DB_USER,DB_PASS,
										array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
				}
				catch (PDOException $e) { die($e->getMessage()); }
			}
			return self::$PDO;
		}
	}
?>
