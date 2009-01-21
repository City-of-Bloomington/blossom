<?php
require_once 'PHPUnit/Framework.php';

require_once 'UnitTests/RoleUnitTest.php';
require_once 'UnitTests/UserUnitTest.php';

class UnitTests extends PHPUnit_Framework_TestSuite
{
    public static function suite()
    {
        $suite = new UnitTests('Unit Tests');

		$suite->addTestSuite('RoleUnitTest');
		$suite->addTestSuite('UserUnitTest');

		return $suite;
	}
}
