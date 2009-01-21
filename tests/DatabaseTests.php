<?php
require_once 'PHPUnit/Framework.php';

require_once 'DatabaseTests/RoleListDbTest.php';
require_once 'DatabaseTests/RoleDbTest.php';
require_once 'DatabaseTests/UserListDbTest.php';
require_once 'DatabaseTests/UserDbTest.php';

class DatabaseTests extends PHPUnit_Framework_TestSuite
{
	protected function setUp()
	{
		$dir = dirname(__FILE__);
		exec('/usr/local/mysql/bin/mysql -u '.DB_USER.' -p'.DB_PASS.' '.DB_NAME." < $dir/testData.sql\n");
	}

	protected function tearDown()
	{
		$dir = dirname(__FILE__);
		exec('/usr/local/mysql/bin/mysql -u '.DB_USER.' -p'.DB_PASS.' '.DB_NAME." < $dir/testData.sql\n");
	}

    public static function suite()
    {
        $suite = new DatabaseTests('Database Tests');

		$suite->addTestSuite('RoleListDbTest');
		$suite->addTestSuite('RoleDbTest');
		$suite->addTestSuite('UserListDbTest');
		$suite->addTestSuite('UserDbTest');

        return $suite;
    }
}
